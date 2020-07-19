'use strict';

const mainContainer = document.querySelector('main'),
	mainButton = document.querySelector('.main-button'),
	movingPanel = document.querySelector('.transformer-container'),
	dimensionBorder = document.querySelector('.dimension-border'),
	formContainer = document.querySelector('.inner-form-container'),
	transformerH2 = document.querySelector('.transformer-h2'),
	form = document.querySelector('.transformer-form'),
	nameContainer = document.querySelector('.name-input-container'),
	nameIcon = document.getElementById('name-icon'),
	nameInput = document.getElementById('name'),
	emailIcon = document.getElementById('email-icon'),
	emailInput = document.getElementById('email'),
	passwordIcon = document.getElementById('password-icon'),
	passwordInput = document.getElementById('password'),
	hiddenSignUp = document.querySelector('.hidden-sign-up'),
	hiddenLogin = document.querySelector('.hidden-login'),
	errorMsg = document.querySelector('.error-msg'),
	url = document.URL;

// Applying values to input elements
// that were passed by our PHP in case of errors
// and adjusting UI to reduce amount of unneeded clicks
// it is done to improve UX
if (url.includes('signup=success') || url.includes('noaccount')) {
	emailInput.focus();
} else if (url.includes('wrongpwd')) {
	passwordInput.focus();
} else if (url.includes('index.php?')) {
	// Adjust panel to LEFT qfter failed Sign Up according to screen width
	if (screen.width > 1333) {
		dimensionBorder.style.cssText = 'transform: translateX(-449%)';
		movingPanel.style.cssText = 'transform: translateX(-88%)';
	}

	form.action = 'auth_logic/signup.php';
	nameContainer.style.cssText = 'display:flex;';
	if (url.includes('name=')) {
		nameInput.value = url.substring(
			url.indexOf('name=') + 5,
			url.indexOf('&email')
		);
	}

	if (url.includes('email=')) {
		emailInput.value = url.substring(url.indexOf('email=') + 6, url.length);
	}

	mainButton.innerHTML = 'SIGN UP';
	mainButton.name = 'sign-up-btn';
	formContainer.style.cssText = 'margin-top: 20px';
}

// Transforming all the parameters for the moving panel
// whenever user click the BLUE BUTTON
mainContainer.addEventListener('click', event => {
	if (event.target.classList.contains('fake-sign-up-btn')) {
		// Different behavior depending on current screen width,
		// consider layouts and BUTTONS change
		if (event.target.classList.contains('hidden-sign-up')) {
			formContainer.style.cssText = 'opacity: 0; transition: all linear 0.5s';
			setTimeout(() => {
				transformerH2.innerHTML = 'Sign Up';
				errorMsg ? errorMsg.remove() : '';
				form.action = 'auth_logic/signup.php';
				nameInput.setAttribute('required', 'true');
				nameContainer.style.cssText =
					'opacity: 1; display:flex; transition: all linear 0.5s';
				mainButton.innerHTML = 'SIGN UP';
				mainButton.name = 'sign-up-btn';
				hiddenLogin.classList.toggle('hidden-btn');
				event.target.classList.toggle('hidden-btn');
				formContainer.style.cssText =
					'opacity: 1; transition: opacity linear 0.5s;';
			}, 500);
		} else {
			dimensionBorder.style.cssText =
				'transform: translateX(-449%); transition: all linear 1s';
			movingPanel.style.cssText =
				'transform: translateX(-88%); transition: all linear 1s';
			formContainer.style.cssText = 'opacity: 0; transition: all linear 0.5s';
			setTimeout(() => {
				transformerH2.innerHTML = 'Sign Up';
				errorMsg ? errorMsg.remove() : '';
				form.action = 'auth_logic/signup.php';
				nameInput.setAttribute('required', 'true');
				nameContainer.style.cssText =
					'opacity: 1; display:flex; transition: all linear 0.5s';
				mainButton.innerHTML = 'SIGN UP';
				mainButton.name = 'sign-up-btn';
				formContainer.style.cssText =
					'opacity: 1; transition: opacity linear 0.5s;';
			}, 500);
		}
	} else if (event.target.classList.contains('fake-login-btn')) {
		// Different behavior depending on current screen width,
		// consider layouts and BUTTONS change
		if (event.target.classList.contains('hidden-login')) {
			formContainer.style.cssText =
				'opacity: 0; transition: opacity linear 0.5s;';

			setTimeout(() => {
				transformerH2.innerHTML = 'Login';
				errorMsg ? errorMsg.remove() : '';
				form.action = 'auth_logic/login.php';
				nameInput.removeAttribute('required');
				nameContainer.style.cssText =
					'opacity: 0; display:none; transition: opacity linear 0.5s';
				mainButton.innerHTML = 'LOGIN';
				mainButton.name = 'login-btn';
				event.target.classList.toggle('hidden-btn');
				hiddenSignUp.classList.toggle('hidden-btn');
				formContainer.style.cssText =
					'opacity: 1; transition: opacity linear 0.5s';
			}, 500);
		} else {
			dimensionBorder.style.cssText =
				'transform: translateX(0px); transition: all linear 1s';
			movingPanel.style.cssText =
				'transform: translateX(0px); transition: all linear 1s';
			formContainer.style.cssText =
				'opacity: 0; transition: opacity linear 0.5s;';

			setTimeout(() => {
				transformerH2.innerHTML = 'Login';
				errorMsg ? errorMsg.remove() : '';
				form.action = 'auth_logic/login.php';
				nameInput.removeAttribute('required');
				nameContainer.style.cssText =
					'opacity: 0; display:none; transition: opacity linear 0.5s';
				mainButton.innerHTML = 'LOGIN';
				mainButton.name = 'login-btn';
				formContainer.style.cssText =
					'opacity: 1; transition: opacity linear 0.5s';
			}, 500);
		}
	}
});

// Changing img src for focused inputs icon
formContainer.addEventListener('focusin', event => {
	if (event.target.id === 'name') {
		nameIcon.src = 'assets/user_active.png';
	} else if (event.target.id === 'email') {
		emailIcon.src = 'assets/mail_active.png';
	} else if (event.target.id === 'password') {
		passwordIcon.src = 'assets/lock_active.png';
	}
});

// Reverting img src for focused input icon
formContainer.addEventListener('focusout', event => {
	if (event.target.id === 'name') {
		nameIcon.src = 'assets/user_inactive.png';
	} else if (event.target.id === 'email') {
		emailIcon.src = 'assets/mail_inactive.png';
	} else if (event.target.id === 'password') {
		passwordIcon.src = 'assets/lock_inactive.png';
	}
});
