# Binding Interfaces
-  Dalam praktek pengembangan perangkat lunak, hal yang bagus ketika membuat sebuah class yang berhubungan dengan logic adalah, membuat interfaces sebagai kontrak
- Harapannya implementasi kelas bisa berbeda-beda tanpa harus mengubah kontrka interfaces
- Laravel memiliki fitur melakukan binding dari interface ke class secara mudah, kita bisa menggunakan function bind(interface,class) atau bind (interface, closure)