MedOptima.prototype.ReservationDoctorCollection = Backbone.Collection.extend({

    model : MedOptima.prototype.ReservationModelDoctor,
    url : '/api/doctor'

});