
interface ResponseServe {
    status: string,
    message: string,
}

interface FormShalawat {
    id: number | null,
    muhudId: number,
    position: number,
    muhudName: string,
    text: string,
    isDiwan: boolean,
    isDiba: boolean,
    translateId: string | null,
    transliteration: string | null,
    createdBy: string,
}

interface ShalawatList {
    id: number | null,
    muhud: Muhud,
    position: number,
    text: string,
    isDiwan: boolean,
    isDiba: boolean,
    translateId: string | null,
    transliteration: string | null,
    audios: [],
    createdBy: string,
}

interface Muhud {
    id: number,
    name: string,
    nameArabic: string,
    position: number,
}
