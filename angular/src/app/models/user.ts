export class User {
    constructor(
        public email: string,
        private plainPassword: string,
        private id?: number,
    ) {}
}