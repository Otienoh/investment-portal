export interface FormRegister {
    firstName: string;
    lastName: string;
    email: string;
    password: string;
    consent: boolean;
}
export interface FormAuth {
    email: string;
    password: string;
    remember: boolean;
}
export interface FormProfile {
    firstName: string;
    lastName: string;
    country: number;
    sex: string;
}
export interface FormArticle {
    title: string;
    content: string;
    sendNotice: boolean;
    preview: File;
}
export interface FormParamsAnalyze {
    monthPeriod: number;
}
export interface FormEditUserAdmin {
    lastName: string;
    firstName: string;
    country: number;
    sex: string;
    role: number;
}
