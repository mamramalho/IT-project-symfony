export class Review {
    constructor(
        public content : string,
        public id?: number,
        public userEmail?: string,
        public vehicleId?: number,
    ) {}
}