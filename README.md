# xss-lab
A realistic phone review blog with a stored XSS vulnerability in the comments section. Users can inject XSS payloads like `&lt;script>alert(1)&lt;/script>` to trigger the vulnerability and reveal a hidden flag. Built with PHP, SQLite, and Docker – runs on port 81. Perfect for learning how stored XSS works in a real-world application context.
