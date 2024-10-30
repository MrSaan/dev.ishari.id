interface ResponseServer {
    status: string,
    message: string,
}

interface Person {
    id: number|null,
    name: string,
    description: string|null,
    avatar: File|null,
}
