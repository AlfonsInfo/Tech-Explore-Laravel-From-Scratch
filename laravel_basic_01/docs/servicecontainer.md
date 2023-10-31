# Service COntainer
- Service Container, merupakan fitur manajemen dependency injection
- Represetansi Service Container bernama class Application

# Application Class
- Kita tidak perlu membuat class Application secara manual, karena sudah dilakukan secara otomatis oleh framework laravel
-  variable $app

# Membuat dependency
- membuat dependency dengan function make(key) terdapat di class Application untuk membuat dependency secara otomatis
- make(key) akan selalu membuat objek baru



# Mengubah cara membuat dependency
- jika ingin define cara pembuatan objek, kita bisa menggunakan method bind ( key, closure)
- kita cukup return data kita inginkan pada function closure
- tidak menggunakan objek yang sama


# Singleton
- Ada saat kita hanya perlu membuat 1 objek saja
- kita bisa menggunakan function singleton 