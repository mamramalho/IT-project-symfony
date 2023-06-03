export class Vehicle {
    constructor(
        private name: string,
        private company: string,
        private type: string,
        private model: string,
        private year: number,
        private registerYear: number,
        private price: number,
        private description: string,
        private color: string,
        private fuel: string,
        private plate: string,
        private km: number,
        private images: string[],
    ) {}
}