function togglePassword(passwordInput) {
   if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
   } else {
      passwordInput.type = 'password';
   }
}
