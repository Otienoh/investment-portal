import { SagaIterator } from "redux-saga";
import { put, takeLatest } from "redux-saga/effects";
import { ApiService } from "../../utils/api";
import { AnyAction } from "redux";
import { InvestmentData } from "../../ts/types/redux/store.types";
import axios from "axios";

const api = new ApiService();
function* authorizationUser(action: AnyAction): Generator {
    const userOrError = yield api
        .post("/api/user/login", action.data)
        .then((response) => response);
    yield put({ type: action.type });
}
function* fetchInvestmentData(action: AnyAction): Generator {
    const data = yield api
        .get("/api/investment-data/portal")
        .then((response) => response);
    yield put({
        type: "SET_PORTAL_DATA",
        data,
    });
}
function* viewNotice(action: AnyAction): Generator {
    try {
        const result = yield axios
            .post("/api/user/notice/view", { id: action.id })
            .then((response) => response.data);
        yield put({
            type: "SET_NOTICE_VIEW",
            noticeId: action.id,
        });
    } catch (error) {
        yield put({
            type: "SET_ERROR_NOTICE_VIEW",
            noticeId: action.id,
        });
    }
}
function* registerUser(action: AnyAction): Generator {
    try {
        const userData = yield axios
            .post("/api/user/register", action.form)
            .then((response) => response.data);
        yield put({
            type: "SET_SUCCESS_REGISTER",
            userData,
        });
    } catch (error) {
        yield put({
            type: "SET_ALERT_ERROR",
            message: error.response.data.error,
        });
    }
}
function* authUser(action: AnyAction): Generator {
    try {
        const userData = yield axios
            .post("/api/user/login", action.userData)
            .then((response) => response.data);
        yield put({
            type: "SET_USER_DATA",
            userData,
        });
    } catch (error) {
        yield put({
            type: "SET_ALERT_ERROR",
            message: error.response.data.error,
        });
    }
}
function* fetchCountries(action: AnyAction): Generator {
    try {
        const countries = yield axios
            .get("/api/other/countries")
            .then((response) => response.data);
        yield put({
            type: "SET_COUNTRIES",
            countries,
        });
    } catch (e) {}
}
function* exitUser(action: AnyAction): Generator {
    try {
        yield axios.get("/api/user/exit").then((response) => response.data);
        yield put({
            type: "SET_EXIT_USER",
        });
    } catch (e) {
        yield put({
            type: "SET_ALERT_ERROR",
            message: "Не удалось выполнить запрос. Попробуйте позже",
        });
    }
}
function* subscribeNews(action: AnyAction): Generator {
    try {
        yield axios
            .post("/api/other/subscribe-email", { email: action.email })
            .then((response) => response.data);
        yield put({
            type: "SET_ALERT_SUCCESS",
            message: "You successfully subscribed portal news",
        });
    } catch (e) {
        yield put({
            type: "SET_ALERT_ERROR",
            message: "Failed subscribe. Try again later",
        });
    }
}
function* fetchNews(action: AnyAction): Generator {
    try {
        const news = yield axios
            .get("/api/investment-data/news")
            .then((response) => response.data);
        yield put({
            type: "SET_NEWS",
            news,
        });
    } catch (e) {}
}

export function* actionMainWatcher(): SagaIterator {
    yield takeLatest("USER_LOGIN", authorizationUser);
    yield takeLatest("FETCH_INVESTMENT_DATA", fetchInvestmentData);
    yield takeLatest("VIEW_NOTICE", viewNotice);
    yield takeLatest("REGISTER_USER", registerUser);
    yield takeLatest("AUTH_USER", authUser);
    yield takeLatest("FETCH_COUNTRIES", fetchCountries);
    yield takeLatest("EXIT_USER", exitUser);
    yield takeLatest("SUBSCRIBE_TO_NEWS", subscribeNews);
    yield takeLatest("FETCH_NEWS", fetchNews);
}
