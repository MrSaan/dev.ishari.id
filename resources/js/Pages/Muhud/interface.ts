
interface ResponseServer {
    status: string,
    message: string,
}


interface FormMuhud {
    id: number | null,
    position: number,
    name: string,
    nameArabic: string | null,
    translateId: string | null,
    transliteration: string | null,
}

interface MuhudList {
    id: number,
    position: number,
    name: string,
    arabic: string,
    translateId: string,
    transliteration: string,
}
