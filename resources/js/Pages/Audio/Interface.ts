interface ResponseServer {
    status: string,
    message: string,
}

interface FormAudio {
    personId: number,
    personName: string,
    description: string,
    audio: File | null,
    textId: number | null,
}

interface Audio {
    text_arabic_id: number,
    person_id: number,
    person: Person,
    path: string,
    description: string,
    created_at: any,
    update_at: any,
}
