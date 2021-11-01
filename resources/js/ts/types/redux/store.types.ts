import { News } from "../state/stock-market.types";

export interface MainStore {
    user: User;
    news: News[];
    investmentData: InvestmentData;
}

export interface InvestmentData {
    bestProfit: number | null;
    worseProfit: number | null;
}

export interface AdminStore {
    investmentIdeas: {
        viewToday: null | number;
        likedToday: null | number;
    };
}

export interface User {
    userName: string;
    firstName: string;
    secondName: string;
    role: string;
    notices: Notice[];
}

export interface Notice {
    id: number;
    title: string;
    description: string;
    viewed: boolean;
    created: string;
}

export interface StoreData {
    main: MainStore;
    admin: AdminStore;
}
