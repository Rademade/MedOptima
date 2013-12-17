 var LoginFrom = function($from, $enter, ajaxUrl) {

	 var idLogin = 'adminLogin';
	 var $login;
	 var idPasswd = 'adminPasswd';
	 var $passwd;
	 var idRemember = 'adminRemember';
	 var $remember;

	 this.getLogin = function() {
		 if (!($login instanceof jQuery)) {
			 $login = $('#' + idLogin);
		 }
		 return $login;
	 };

	 this.getPasswd = function() {
		 if (!($passwd instanceof jQuery)) {
			 $passwd = $('#' + idPasswd);
		 }
		 return $passwd;
	 };

	 this.getRemeber = function() {
		 if (!($remember instanceof jQuery)) {
			 $remember = $('#' + idRemember);
		 }
		 return $remember;
	 };

	 this.isRemeber = function() {
		 return this.getRemeber().attr('checked') === 'checked'
	 };

	 this._login = function() {
		 $.ajax({
	        type: "POST",
	        url: ajaxUrl,
	        dataType: "json",
	        data: {
	        	type: 'userLogin',
	        	mail: this.getLogin().val(),
	        	passwd: this.getPasswd().val(),
	        	remember: this.isRemeber() ? 1 : 0
	        },
	        success: function(data) {
                var loginStatus = parseInt(data.login, 10);
                if (loginStatus === 2) {
	        		window.location.reload();
	        		return true;
	        	} else {
                    if (loginStatus === 1) {
                        Adminka.Error("You don't have permission for access");
    	        	} else {
                        Adminka.Error('Wrong login or password');
                    }
                    return false;
	        	}
	        }.of(this)
		 });
	 };

	 this._bindEnter = function($input) {
		 $input.keypress(function(e){
	    	if (e.which == 13) {
	    		$from.submit();
	    	}
		});
	 };

	 this._bindButtons = function() {
		 $enter.click(function(){
			 $from.submit();
		 });
	 };

	 this._getRules = function() {
		 var rules = {};
		 rules[ idLogin ] = {
			required: true,
			email: true
		 };
		 rules[idPasswd] = {
			required: true,
			minlength:6,
			maxlength:100
		 };
		 return rules;
	 };

	 this._getMassages = function() {
		 var messages = {};
		 messages[ idLogin ] = {
			required: 'Enter your login',
			email: 'Wrong login format'
		 };
		 messages[ idPasswd ] = {
			required: 'Enter your password',
			minlength: 'Password is very short',
			maxlength: 'Password is overlong'
		 };
		 return messages;
	 };

	 this._bindValidate = function() {
		 $from.validate({
			submitHandler: function(form) {
				this._login();
				return false;
			}.of(this),
		 	rules: this._getRules(),
		 	messages: this._getMassages()
		});
	 };


	 this._bindButtons();
	 this._bindValidate();
	 this._bindEnter( this.getLogin() );
	 this._bindEnter( this.getPasswd() );

 };