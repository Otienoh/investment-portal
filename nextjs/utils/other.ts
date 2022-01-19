import {
    align,
    font,
    fontColor,
    fontSize,
    formatBlock,
    hiliteColor,
    horizontalRule,
    lineHeight,
    list,
    paragraphStyle,
    table,
    template,
    textStyle,
    image,
    link,
} from "suneditor/src/plugins";

export const settingsSunEditor = {
    showPathLabel: false,
    buttonList: [
        ["undo", "redo"],
        ["font", "fontSize", "formatBlock"],
        ["paragraphStyle"],
        ["bold", "underline", "italic", "strike", "subscript", "superscript"],
        ["fontColor", "hiliteColor"],
        ["removeFormat"],
        "/", // Line break
        ["outdent", "indent"],
        ["align", "horizontalRule", "list", "lineHeight"],
        ["table", "link", "image"],
    ],
    formats: ["p", "div", "h1", "h2", "h3", "h4", "h5", "h6"],
    font: [
        "Arial",
        "Calibri",
        "Comic Sans",
        "Courier",
        "Garamond",
        "Georgia",
        "Impact",
        "Lucida Console",
        "Palatino Linotype",
        "Segoe UI",
        "Tahoma",
        "Times New Roman",
        "Trebuchet MS",
    ],
};
export const getPhoto = (photo) => {
    if (!photo) {
        return null;
    }
    return typeof photo === "string"
        ? `${process.env.API_URL}/storage/${photo}`
        : URL.createObjectURL(photo);
};
