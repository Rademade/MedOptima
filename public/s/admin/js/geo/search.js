var geoSearch = function() {
	
	var geo = new google.maps.Geocoder();
	var cache = [];
	
	this.getResults = function(req, cb) {
		var address = req.term;
		if (cache[address]) {
			cb(cache[address]);
		} else {
			this.getDataByAddress(address, function(data){
				cache[address] = data;
				cb(data);
			});	
		}
	};
	
	this._request = function(q, cb) {
		geo.geocode(q, function(results, status) {
		    var data = [];
			if (status == google.maps.GeocoderStatus.OK) {
				data = results;
		    }
			cb(data);
		});
	};
	
	this.getDataByLocation = function(lat, lng, cb) {
		this._request({
			'location': new google.maps.LatLng(lat, lng)
		}, function(data){
			cb(data);
		});
	};
	
	this.getDataByAddress = function(fullAddress, cb) {
		this._request({
			'address': fullAddress
		}, function(data){
			cb(data);
		});
	};
	
};