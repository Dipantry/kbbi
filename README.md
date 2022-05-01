# Kamus Besar Bahasa Indonesia (KBBI) Scraper

[![Release](https://img.shields.io/github/v/release/dipantry/kbbi?label=Release&sort=semver&style=flat-square)](https://github.com/dipantry/kbbi/releases)
[![Packagist](https://img.shields.io/packagist/v/dipantry/kbbi?label=Packagist&style=flat-square)](https://packagist.org/packages/dipantry/kbbi)
![PHP Version](https://img.shields.io/packagist/php-v/dipantry/kbbi?label=PHP%20Version)
![GitHub stars](https://img.shields.io/github/stars/dipantry/kbbi?label=Stars&style=flat-square)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?label=License&style=flat-square)](https://opensource.org/licenses/MIT)
<br>
![run-tests](https://github.com/dipantry/kbbi/workflows/run-tests/badge.svg)
[![StyleCI](https://github.styleci.io/repos/485616077/shield?branch=main)](https://github.styleci.io/repos/485616077?branch=main)

Library PHP untuk mengambil data dari [KBBI](https://kbbi.kemdikbud.go.id/). Library ini dibuat untuk mempermudah penggunaan KBBI dengan cara melakukan scraping data dari website KBBI resmi milik Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi (Kemdikbud).

# Instalasi
```sh
composer require dipantry/kbbi
```

# Cara Penggunaan
## Request
```php
use Dipantry\KBBI\KBBI;

$response = (new KBBI())->request('demokrasi');
```

## Response
Data response yang dikembalikan pada variabel `$response` berbentuk json. Jika Anda ingin menggunakannya pada kodingan PHP, Anda dapat menggunakan fungsi `json_decode` untuk mengubah string json menjadi array.

### Success Response
```json
{
  "success": true,
  "code": 200,
  "message": "Search word success",
  "data": [
    {
      "spelling": "de.mo.kra.si \/d\u00e9mokrasi\/",
      "meanings": [
        {
          "description": "(bentuk atau sistem) pemerintahan yang seluruh rakyatnya turut serta memerintah dengan perantaraan wakilnya; pemerintahan rakyat",
          "categories": [
            {
              "code": "n",
              "description": "Nomina: kata benda"
            },
            {
              "code" :"Pol",
              "description": "Politik dan Pemerintahan: -"
            }
          ]
        },
        {
          "description": "gagasan atau pandangan hidup yang mengutamakan persamaan hak dan kewajiban serta perlakuan yang sama bagi semua warga negara",
          "categories": [
            {
              "code": "n",
              "description": "Nomina: kata benda"
            },
            {
              "code": "Pol",
              "description": "Politik dan Pemerintahan: -"
            }
          ]
        }
      ]
    }
  ]
}
```

### Failed Response
```json
{
  "success":false,
  "code": 400,
  "message": "Entri tidak ditemukan.",
  "data": null
}
```

### Penjelasan Response
| Key           | Deskripsi                                  |
|---------------|--------------------------------------------|
| `success`     | `true` jika berhasil, `false` ketika gagal |
| `code`        | Kode status                                |
| `message`     | Pesan berhasil/error                       |
| `data`        | Data yang dikembalikan                     |
| ---           | ---                                        |
| `spelling`    | Cara penyebutan kata                       |
| `meanings`    | Definisi kata                              |
| ---           | ---                                        |
| `description` | Arti kata                                  |
| `categories`  | Kategori kata                              |
| ---           | --                                         |
| `code`        | Kode kategori                              |
| `description` | Deskripsi kategori                         |


---
# Testing
Jalankan testing dengan menjalankan perintah berikut ini
```sh
vendor/bin/phpunit
```
