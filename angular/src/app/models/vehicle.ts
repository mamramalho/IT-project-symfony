export class Vehicle {
    constructor(
        public name: string,
        public company: string,
        public type: string,
        public model: string,
        public year: number,
        public registerYear: number,
        public price: number,
        public description: string,
        public color: string,
        public fuel: string,
        public plate: string,
        public kms: number,
        public images: string[],
        public city: string[],
        public id?: number,
    ) {}
}