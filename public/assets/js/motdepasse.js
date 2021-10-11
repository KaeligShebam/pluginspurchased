const passwordToggle = document.querySelector('.js-password-toggle');
        passwordToggle.addEventListener('change', function () {
          const password = document.querySelector('.js-password'),
            passwordLabel = document.querySelector('.js-password-label');
          if (password.type === 'password') {
          password.type = 'text';
          passwordLabel.innerHTML = '<i class="fas fa-eye"></i>';
          } else {
          password.type = 'password';
          passwordLabel.innerHTML = '<i class="fas fa-eye-slash"></i>';
          }
          password.focus();
        });