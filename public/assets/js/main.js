$(function() {

	//Корзина

	$('.add-to-cart').on('click', function (e){ //выбираем все елементы с классом add-to-cart, назначаем обработчик события click, когда пользователь килакает срабатывает функция preventDefault

		e.preventDefault(); //отменяем переход на другую страницу
		const id = $(this).data('id'); //получаем знач id на который кликнул пользователь
		const qty = $('#input-quantity').val() ? $('#input-quantity').val() : 1; //получаем из поля для ввода кол-ва, если оно есть то его берем если нету то 1
		const $this = $(this); //возьмем текущий объект

		console.log(id, qty);

		$.ajax({ //АЯКС запрос
			url: 'cart/add', //url на который будет отправлен запрос
			type: 'GET', //метод передачи данных
			data: {id: id, qty: qty}, //данные которые будем отправлять
			success: function (res){ //ответ сохраним в res
				console.log(res);
			},
			error:function (){ //в случае ошибки
				alert('Error!');
			}
		})
	});

	//Корзина

	$('.open-search').click(function(e) {
		e.preventDefault();
		$('#search').addClass('active');
	});
	$('.close-search').click(function() {
		$('#search').removeClass('active');
	});

	$(window).scroll(function() {
		if ($(this).scrollTop() > 200) {
			$('#top').fadeIn();
		} else {
			$('#top').fadeOut();
		}
	});

	$('#top').click(function() {
		$('body, html').animate({scrollTop:0}, 700);
	});

	$('.sidebar-toggler .btn').click(function() {
		$('.sidebar-toggle').slideToggle();
	});

	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled: true
		},
		removalDelay: 500,
		callbacks: {
			beforeOpen: function() {
				this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
				this.st.mainClass = this.st.el.attr('data-effect');
			}
		}
	});

	$('#languages button').on('click', function (){ //Обращаемя к languages внутри button, отслеживаем событие on click, по этому событию вызывается функция
		const lang_code = $(this).data('langcode'); //<button class="dropdown-item" data-langcode="ru"> или en
		//запишем в lang_code значение атрибута langcode
		window.location = PATH + '/language/change?lang=' + lang_code; //Перепишем url, PATH - путь к нашему сайту и пристыковываем к language(контроллер) и change(экшен), GET параметр + значение lang_code(ru, en) язык на который хотим перейти
	});

});