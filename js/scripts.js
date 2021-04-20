(function ($) {
	const
		mail = $('#mail'),
		pass = $('#pass'),
		showPassword = $('#js-show-password'),
		passStrength = $('#js-pass-strength'),
		signUpBtn = $('#signup-btn'),
		noSelectDatetime = $('#no-select-datetime');


	// パスワード表示非表示切り替え
	let isShow = false;
	showPassword.on('click', function () {
		if (isShow) {
			// 表示状態なら非表示化
			pass.attr('type', 'password');
			$(this).text('パスワード表示');
		} else {
			// そうでなければ表示
			pass.attr('type', 'text');
			$(this).text('パスワード非表示');
		}

		// 表示状態を切り替え
		isShow = isShow ? false : true;
	});

	// 日付選択カレンダー
	$('.datepicker').datepicker({
		language: 'ja',
		format: 'yyyy/mm/dd'
	});

	// 新規ユーザ登録時のパスワードの強度チェック
	pass.on('keyup',function () {
		passStrength.html(checkStrength(pass.val()))
	})
	function checkStrength(password) {

		let strength = 0; //強さ

		if (password.length <= 0) {
			passStrength.removeClass()
			passStrength.addClass('alert')
			passStrength.addClass('alert-info')
			signUpBtn.attr('disabled',true)
			return 'パスワードを入力してください。';
		}
		if (password.length < 7) {
			passStrength.removeClass()
			passStrength.addClass('alert')
			passStrength.addClass('alert-danger')
			signUpBtn.attr('disabled',true)
			return 'パスワードが短すぎます。'
		}

		// 文字数が8より大きいければ+1
		if (password.length > 8) strength += 1
		// 英字の大文字と小文字を含んでいれば+1
		if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
		// 英字と数字を含んでいれば+1
		if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
		// 記号を含んでいれば+1
		if (password.match(/([!,%,&,@,#,$,^,*,?,_,~,(,)])/)) strength += 1
		// 記号を2つ含んでいれば+1
		if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~,(,)].*[!,%,&,@,#,$,^,*,?,_,~,(,)])/)) strength += 1

		// 点数を元に強さを計測
		if (strength <= 2) {
			passStrength.removeClass()
			passStrength.addClass('alert')
			passStrength.addClass('alert-warning')
			signUpBtn.attr('disabled',true)
			return 'パスワードの強度：弱'
		} else if (strength <= 3) {
			passStrength.removeClass()
			passStrength.addClass('alert')
			passStrength.addClass('alert-primary')
			signUpBtn.attr('disabled',false)
			return 'パスワードの強度：中'
		} else if(strength <= 4) {
			passStrength.removeClass()
			passStrength.addClass('alert')
			passStrength.addClass('alert-success')
			signUpBtn.attr('disabled',false)
			return 'パスワードの強度：強'
		} else {
			passStrength.removeClass()
			passStrength.addClass('alert')
			passStrength.addClass('alert-success')
			signUpBtn.attr('disabled',false)
			return 'パスワードの強度：とても強い'
		}
	}


	// 日時を指定しない
	let 
		isDatetimeDisabled = true,
		timesList = [
			'hour', 'min', 'sec'
		];
	noSelectDatetime.on('click', function(){
		$("input[name='date-from']").attr('disabled',isDatetimeDisabled);
		$("input[name='date-to']").attr('disabled',isDatetimeDisabled);
		$("select[name='radio-time']").attr('disabled',isDatetimeDisabled);

		// forEachで回して disabled 状態切り替え
		timesList.forEach(function(name) {
			$("select[name='"+name+"-from']").attr('disabled',isDatetimeDisabled);
			$("select[name='"+name+"-to']").attr('disabled',isDatetimeDisabled);
			console.log(name);
		});

		isDatetimeDisabled = isDatetimeDisabled ? false : true;
	});

}(jQuery));