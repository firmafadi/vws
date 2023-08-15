define({ "api": [
  {
    "type": "post",
    "url": "/token",
    "title": "Request token.",
    "name": "GetToken",
    "group": "Auth",
    "version": "0.1.2",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Request token yang akan digunakan untuk autentikasi pengiriman data ke VTax Web Service.</p>",
    "examples": [
      {
        "title": "Contoh Penggunaan :",
        "content": "http://119.252.160.220/vtax-web-service/token",
        "type": "json"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Authorized_Code",
            "description": "<p>Kode verifikasi agar bisa mendapatkan token.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n    Authorized_Code : XXXXX\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status response, jika berhasil bernilai true dan jika gagal bernilai false.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Deskripsi response.</p>"
          },
          {
            "group": "Success 200",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>Informasi token.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.token",
            "description": "<p>Token.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.expired",
            "description": "<p>Durasi berlakunya token (dalam format time).</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Respon: ",
          "content": "{\n      \"status\": true,\n      \"message\": \"Token created!\",\n      \"data\": {\n          \"token\": \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1Nzg1NDMyOTMsIm5iZiI6MTU3ODU0MzMwMywiZXhwIjoxNTc4NTQ2ODkzLCJkYXRhIjpbXX0.KizrzfUpsjFbW5nTVuXNCeCDbdTaB_N5ul0fungq4oI\",\n          \"expired\": \"01:00:00\"\n      }\n }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Parameter-Invalid",
            "description": "<p>Parameter tidak sesuai.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Authorized_Code-Invalid",
            "description": "<p>Authorized_Code tidak terdaftar.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response (contoh):",
          "content": "{\n     \"status\": false,\n     \"message\": \"Parameter invalid!\",\n     \"data\": null\n}",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/AuthController.php",
    "groupTitle": "Auth"
  },
  {
    "type": "POST",
    "url": "/bphtb/penerimaan",
    "title": "Daftar Penerimaan",
    "name": "getDaftarPenerimaan",
    "group": "BPHTB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil daftar penerimaan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "bulan",
            "description": "<p>Bulan pajak PBB. Diisi nomor bulan (1 sampai 12).</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015,\n\t\t\"bulan\" : 6\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/bphtb/penerimaan",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.penerimaan",
            "description": "<p>Daftar penerimaan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.wp_nama",
            "description": "<p>Nama wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.penerimaan.harus_dibayar",
            "description": "<p>Nilai pajak yang harus dibayar.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.waktu_pembayaran",
            "description": "<p>Waktu pembayaran pajak.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t    \"status\": true,\n\t    \"message\": \"OK\",\n\t    \"data\": {\n\t        \"penerimaan\": [\n\t            {\n\t                \"wp_nama\": \"RIZAL\",\n\t                \"harus_dibayar\": 90000,\n\t                \"waktu_pembayaran\": \"2015-02-02 12:31:00\"\n\t            },\n\t            {\n\t                \"wp_nama\": \"WILLIS\",\n\t                \"harus_dibayar\": 9500000,\n\t                \"waktu_pembayaran\": \"2015-02-02 12:31:00\"\n\t            }\n\t        ]\n\t    }\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/BPHTBController.php",
    "groupTitle": "BPHTB"
  },
  {
    "type": "POST",
    "url": "/bphtb/penerimaan/kelurahan",
    "title": "Daftar Penerimaan Kelurahan",
    "name": "getDaftarPenerimaanKelurahan",
    "group": "BPHTB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil daftar penerimaan kelurahan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "bulan",
            "description": "<p>Bulan pajak PBB. Diisi nomor bulan (1 sampai 12).</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "kelurahan",
            "description": "<p>Nama kelurahan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015,\n\t\t\"bulan\" : 6\n\t\t\"kelurahan\" : \"CIKUTRA\"\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/bphtb/penerimaan/kelurahan",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.penerimaan",
            "description": "<p>Daftar penerimaan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.kelurahan",
            "description": "<p>Nama kelurahan.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.penerimaan.harus_dibayar",
            "description": "<p>Nilai pajak yang harus dibayar.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t    \"status\": true,\n\t    \"message\": \"OK\",\n\t    \"data\": {\n\t        \"penerimaan\": [\n\t            {\n\t                \"kelurahan\": \"CIKUTRA\",\n\t                \"harus_dibayar\": 90000\n\t            },\n\t            {\n\t                \"wp_nama\": \"CIBEUNYING\",\n\t                \"harus_dibayar\": 9500000\n\t            }\n\t        ]\n\t    }\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/BPHTBController.php",
    "groupTitle": "BPHTB"
  },
  {
    "type": "POST",
    "url": "/bphtb/penerimaan/target",
    "title": "Daftar Target Penerimaan",
    "name": "getDaftarTargetPenerimaan",
    "group": "BPHTB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil daftar target penerimaan pada tahun tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Paling lama tahun 2018.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/bphtb/penerimaan/target",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.target_penerimaan",
            "description": "<p>Daftar target penerimaan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.target_penerimaan.wp_nama",
            "description": "<p>Nama wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.target_penerimaan.harus_dibayar",
            "description": "<p>Nilai pajak yang harus dibayar.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t\t\"status\": true,\n\t\t\"message\": \"OK\",\n\t\t\"data\": {\n\t\t\t\"target_penerimaan\": [{\n\t\t\t\t\t\"wp_nama\": \"YULIA PUSPITA SARI\",\n\t\t\t\t\t\"harus_dibayar\": 1250000\n\t\t\t\t},\n\t\t\t\t{\n\t\t\t\t\t\"wp_nama\": \"TUTIK\",\n\t\t\t\t\t\"harus_dibayar\": 500000\n\t\t\t\t}\n\t\t\t]\n\t\t}\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/BPHTBController.php",
    "groupTitle": "BPHTB"
  },
  {
    "type": "POST",
    "url": "/bphtb/tunggakan",
    "title": "Daftar Tunggakan",
    "name": "getDaftarTunggakan",
    "group": "BPHTB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil daftar tunggakan pada tahun tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/bphtb/tunggakan",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.tunggakan",
            "description": "<p>Daftar tunggakan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.tunggakan.wp_nama",
            "description": "<p>Nama wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.tunggakan.harus_dibayar",
            "description": "<p>Nilai pajak yang harus dibayar.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t    \"status\": true,\n\t    \"message\": \"OK\",\n\t    \"data\": {\n\t        \"tunggakan\": [\n\t            {\n\t                \"wp_nama\": \"MUSADDAD\",\n\t                \"harus_dibayar\": 9500000\n\t            },\n\t            {\n\t                \"wp_nama\": \"MUSADDAD\",\n\t                \"harus_dibayar\": 9500000\n\t            }\n\t        ]\n\t    }\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/BPHTBController.php",
    "groupTitle": "BPHTB"
  },
  {
    "type": "POST",
    "url": "/bphtb/penerimaan/target/total",
    "title": "Total Target Penerimaan",
    "name": "getTotalTargetPenerimaan",
    "group": "BPHTB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil total target penerimaan pada tahun tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/bphtb/penerimaan/target/total",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.total_target_penerimaan",
            "description": "<p>Total target penerimaan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t    \"status\": true,\n\t    \"message\": \"OK\",\n\t    \"data\": {\n\t        \"total_target_penerimaan\": 1191863520\n\t    }\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/BPHTBController.php",
    "groupTitle": "BPHTB"
  },
  {
    "type": "POST",
    "url": "/bphtb/tunggakan/total",
    "title": "Total Tunggakan",
    "name": "getTotalTunggakan",
    "group": "BPHTB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil total tunggakan pada tahun tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/bphtb/tunggakan/total",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.total_tunggakan",
            "description": "<p>Total tunggakan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t    \"status\": true,\n\t    \"message\": \"OK\",\n\t    \"data\": {\n\t        \"total_tunggakan\": 1191863520\n\t    }\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/BPHTBController.php",
    "groupTitle": "BPHTB"
  },
  {
    "type": "POST",
    "url": "/pbb/penerimaan",
    "title": "Daftar Penerimaan",
    "name": "getDaftarPenerimaan",
    "group": "PBB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil daftar penerimaan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "bulan",
            "description": "<p>Bulan pajak PBB. Diisi nomor bulan (1 sampai 12)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015,\n\t\t\"bulan\"\t: 6\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/pbb/penerimaan",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.penerimaan",
            "description": "<p>Daftar penerimaan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.nop",
            "description": "<p>Nomor NOP.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.wp_nama",
            "description": "<p>Nama wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.penerimaan.harus_dibayar",
            "description": "<p>Nilai pajak yang haris dibayar.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.penerimaan.denda",
            "description": "<p>Nilai denda pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.penerimaan.total_bayar",
            "description": "<p>Nilai yang dibayarkan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.tanggal_bayar",
            "description": "<p>Tanggal pembayaran pajak.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t\t\"status\": true,\n\t\t\"message\": \"OK\",\n\t\t\"data\": {\n\t\t\t\"penerimaan\": [{\n\t\t\t\t\t\"nop\": \"167106000500230340\",\n\t\t\t\t\t\"wp_nama\": \"KENCANA TENGGONO, CS\",\n\t\t\t\t\t\"harus_dibayar\": 180580,\n\t\t\t\t\t\"denda\": 14446,\n\t\t\t\t\t\"total_bayar\": 195026,\n\t\t\t\t\t\"tanggal_bayar\": \"2016-01-25\"\n\t\t\t\t},\n\t\t\t\t{\n\t\t\t\t\t\"nop\": \"167106000500230400\",\n\t\t\t\t\t\"wp_nama\": \"KENCANA TENGGONO, CS\",\n\t\t\t\t\t\"harus_dibayar\": 164340,\n\t\t\t\t\t\"denda\": 13147,\n\t\t\t\t\t\"total_bayar\": 177487,\n\t\t\t\t\t\"tanggal_bayar\": \"2016-01-28\"\n\t\t\t\t}\n\t\t\t]\n\t\t}\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PBBController.php",
    "groupTitle": "PBB"
  },
  {
    "type": "POST",
    "url": "/pbb/penerimaan/kelurahan",
    "title": "Daftar Penerimaan Kelurahan",
    "name": "getDaftarPenerimaanKelurahan",
    "group": "PBB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil daftar penerimaan kelurahan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "bulan",
            "description": "<p>Bulan pajak PBB. Diisi nomor bulan (1 sampai 12).</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "kode_kelurahan",
            "description": "<p>Kode kelurahan. Jika tidak diisi, akan menampilkan penerimaan dari seluruh kelurahan. Jika diisi, hanya akan menampilkan penerimaan dari kode kelurahan yang dimasukkan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015,\n\t\t\"bulan\"\t: 6,\n\t\t\"kode_kelurahan\" : \"1671090005\"\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/pbb/penerimaan/kelurahan",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.penerimaan",
            "description": "<p>Daftar penerimaan kelurahan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.kode_kelurahan",
            "description": "<p>Kode kelurahan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.nama_kelurahan",
            "description": "<p>Nama kelurahan.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.penerimaan.harus_dibayar",
            "description": "<p>Nilai pajak yang haris dibayar.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.penerimaan.denda",
            "description": "<p>Nilai denda pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.penerimaan.total_bayar",
            "description": "<p>Nilai yang dibayarkan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t    \"status\": true,\n\t    \"message\": \"OK\",\n\t    \"data\": {\n\t        \"penerimaan\": [\n\t            {\n\t                \"kode_kelurahan\": \"1671090005\",\n\t                \"nama_kelurahan\": \"TALANG AMAN\",\n\t                \"harus_dibayar\": 2806400,\n\t                \"denda\": 325180,\n\t                \"total_bayar\": 3131580\n\t            }\n\t        ]\n\t    }\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PBBController.php",
    "groupTitle": "PBB"
  },
  {
    "type": "POST",
    "url": "/pbb/tunggakan",
    "title": "Daftar Tunggakan",
    "name": "getDaftarTunggakan",
    "group": "PBB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil daftar tunggakan pada tahun tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/pbb/tunggakan",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.tunggakan",
            "description": "<p>Daftar tunggakan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.tunggakan.nop",
            "description": "<p>Nomor NOP.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.tunggakan.wp_nama",
            "description": "<p>Nama wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.penerimaan.harus_dibayar",
            "description": "<p>Nilai pajak yang haris dibayar.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t\t\"status\": true,\n\t\t\"message\": \"OK\",\n\t\t\"data\": {\n\t\t\t\"tunggakan\": [{\n\t\t\t\t\t\"nop\": \"167101000412130031\",\n\t\t\t\t\t\"wp_nama\": \"NINDI LARASSATI\",\n\t\t\t\t\t\"harus_dibayar\": 25000\n\t\t\t\t},\n\t\t\t\t{\n\t\t\t\t\t\"nop\": \"167106000401700610\",\n\t\t\t\t\t\"wp_nama\": \"NY SRI MULYANA WIRAWAN\",\n\t\t\t\t\t\"harus_dibayar\": 23858600\n\t\t\t\t}\n\t\t\t]\n\t\t}\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PBBController.php",
    "groupTitle": "PBB"
  },
  {
    "type": "POST",
    "url": "/pbb/ketetapan/total",
    "title": "Total Ketetapan",
    "name": "getTotalKetetapan",
    "group": "PBB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil total ketetapan di tahun tertentu, sesuai parameter tahun.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/pbb/ketetapan/total",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.total_ketetapan",
            "description": "<p>Total pajak yang harus dibayar (total ketetapan). //  * @apiSuccess {Number} data.total_ketetapan.harus_dibayar Nilai pajak yang haris dibayar. //  * @apiSuccess {Number} data.total_ketetapan.denda Nilai denda pajak. //  * @apiSuccess {Number} data.total_ketetapan.total_bayar Nilai yang dibayarkan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t    \"status\": true,\n\t    \"message\": \"OK\",\n\t    \"data\": {\n\t        \"total_ketetapan\": {\n\t                \"harus_dibayar\": 25000,\n\t                \"denda\": 3500,\n\t                \"total_bayar\": 28500\n\t            }\n\t    }\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PBBController.php",
    "groupTitle": "PBB"
  },
  {
    "type": "POST",
    "url": "/pbb/penerimaan/total",
    "title": "Total Penerimaan",
    "name": "getTotalPenerimaan",
    "group": "PBB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil total penerimaan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "bulan",
            "description": "<p>Bulan pajak PBB. Diisi nomor bulan (1 sampai 12)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015,\n\t\t\"bulan\"\t: 6\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/pbb/penerimaan/total",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.total_penerimaan",
            "description": "<p>Data total penerimaan.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.total_penerimaan.harus_dibayar",
            "description": "<p>Nilai pajak yang haris dibayar.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.total_penerimaan.denda",
            "description": "<p>Nilai denda pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.total_penerimaan.total_bayar",
            "description": "<p>Nilai yang dibayarkan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t    \"status\": true,\n\t    \"message\": \"OK\",\n\t    \"data\": {\n\t        \"total_penerimaan\": {\n\t                \"harus_dibayar\": 25000,\n\t                \"denda\": 3500,\n\t                \"total_bayar\": 28500\n\t            }\n\t    }\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PBBController.php",
    "groupTitle": "PBB"
  },
  {
    "type": "POST",
    "url": "/pbb/tunggakan/total",
    "title": "Total Tunggakan",
    "name": "getTotalTunggakan",
    "group": "PBB",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil total tunggakan pada tahun tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Mandatory Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak PBB. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/pbb/tunggakan/total",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data.total_tunggakan",
            "description": "<p>Data total tunggakan.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.total_tunggakan.harus_dibayar",
            "description": "<p>Nilai pajak yang haris dibayar.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.total_tunggakan.denda",
            "description": "<p>Nilai denda pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.total_tunggakan.total_bayar",
            "description": "<p>Nilai yang dibayarkan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\t{\n\t    \"status\": true,\n\t    \"message\": \"OK\",\n\t    \"data\": {\n\t        \"total_tunggakan\": {\n\t                \"harus_dibayar\": 25000,\n\t                \"denda\": 3500,\n\t                \"total_bayar\": 28500\n\t            }\n\t    }\n\t}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PBBController.php",
    "groupTitle": "PBB"
  },
  {
    "type": "post",
    "url": "/patda/inquiry/profil-tagihan",
    "title": "Get Profil Tagihan",
    "name": "GetTagihan",
    "group": "Patda",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil data profil tagihan (9 Pajak).</p>",
    "examples": [
      {
        "title": "Contoh Penggunaan :",
        "content": "http://119.252.160.220/vtax-web-service/patda/inquiry/profil-tagihan",
        "type": "json"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "npwpd",
            "description": "<p>NPWPD.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"npwpd\" : \"P2020033870\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status response, jika berhasil bernilai true dan jika gagal bernilai false.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Deskripsi response.</p>"
          },
          {
            "group": "Success 200",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>Informasi tagihan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.NPWPD",
            "description": "<p>NPWPD.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.NAMA_WP",
            "description": "<p>Nama wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.ALAMAT_WP",
            "description": "<p>Alamat wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.NAMA_OP",
            "description": "<p>Nama objek pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.JENIS_PAJAK",
            "description": "<p>Jenis pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.TAHUN_PAJAK",
            "description": "<p>Tahun pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.BULAN_PAJAK",
            "description": "<p>Periode pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.MASA_PAJAK_AWAL",
            "description": "<p>Tanggal masa awal pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.MASA_PAJAK_AKHIR",
            "description": "<p>Tanggal masa akhir pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.SPTPD",
            "description": "<p>Nomor SPTPD.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.SSPD",
            "description": "<p>Nomor SSPD.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.TAGIHAN",
            "description": "<p>Tagihan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.DENDA",
            "description": "<p>Denda.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.TGL_BAYAR",
            "description": "<p>Tanggal bayar.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.STATUS_BAYAR",
            "description": "<p>Status bayar.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Respon: ",
          "content": "{\n     \"status\": true,\n\t\t\"message\": \"Record exist!\",\n\t\t\"data\": {\n  \t\t\"NPWPD\": \"P2020033870\",\n \t\t\"NAMA_WP\": \"JAMAL\",\n  \t\t\"ALAMAT_WP\": \"JL.KOL.H.BURLIAN NO.89\",\n  \t\t\"NAMA_OP\": \"RM ISTANA BUNDA I\",\n  \t\t\"ALAMAT_OP\": \"JL.KOL.H.BURLIAN NO.189\",\n  \t\t\"JENIS_PAJAK\": \"Restoran\",\n  \t\t\"TAHUN_PAJAK\": \"2017\",\n  \t\t\"BULAN_PAJAK\": \"01\",\n  \t\t\"MASA_PAJAK_AWAL\": \"2017-01-01\",\n  \t\t\"MASA_PAJAK_AKHIR\": \"2017-01-31\",\n  \t\t\"SPTPD\": \"900001527/RES/17\",\n  \t\t\"SSPD\": \"900001527/RES/17\",\n  \t\t\"TAGIHAN\": \"1500000.00\",\n  \t\t\"DENDA\": \"0\",\n  \t\t\"TGL_BAYAR\": \"2017-02-14 14:26:11\",\n  \t\t\"STATUS_BAYAR\": \"Sudah Bayar\"\n\t\t}\n }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Parameter-Invalid",
            "description": "<p>Parameter tidak sesuai.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Token-Ivalid",
            "description": "<p>Token salah, expired, dll.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "No-Record",
            "description": "<p>Data tidak ada.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response (contoh):",
          "content": "{\n     \"status\": false,\n     \"message\": \"Parameter invalid!\",\n}",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PATDAController.php",
    "groupTitle": "Patda"
  },
  {
    "type": "post",
    "url": "/patda/inquiry/profil-wp",
    "title": "Get Profil Wajib Pajak",
    "name": "GetWP",
    "group": "Patda",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil data profil wajib pajak (9 Pajak).</p>",
    "examples": [
      {
        "title": "Contoh Penggunaan :",
        "content": "http://119.252.160.220/vtax-web-service/patda/inquiry/profil-wp",
        "type": "json"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "npwpd",
            "description": "<p>NPWPD.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"npwpd\" : \"P210000080101\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status response, jika berhasil bernilai true dan jika gagal bernilai false.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Deskripsi response.</p>"
          },
          {
            "group": "Success 200",
            "type": "object",
            "optional": false,
            "field": "data",
            "description": "<p>Informasi wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.NPWPD",
            "description": "<p>NPWPD.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.NAMA_WP",
            "description": "<p>Nama wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.ALAMAT_WP",
            "description": "<p>Alamat wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.KELURAHAN_WP",
            "description": "<p>Kelurahan (alamat) wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.KECAMATAN_WP",
            "description": "<p>Kecamatan (alamat) wajib pajak.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Respon: ",
          "content": "{\n     \"status\": true,\n\t\t\"message\": \"Record exist!\",\n\t\t\"data\": {\n  \t\t\t\"NPWPD\": \"P210000080101\",\n  \t\t\t\"NAMA_WP\": \"Hotel semeru\",\n  \t\t\t\"ALAMAT_WP\": \"Jl. A. Yani 144 Mintaragen\",\n  \t\t\t\"KELURAHAN_WP\": \"RANDUGUNTING\",\n  \t\t\t\"KECAMATAN_WP\": \"TEGAL SELATAN\"\n\t\t}\n }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Parameter-Invalid",
            "description": "<p>Parameter tidak sesuai.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Token-Ivalid",
            "description": "<p>Token salah, expired, dll.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "No-Record",
            "description": "<p>Data tidak ada.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response (contoh):",
          "content": "{\n     \"status\": false,\n     \"message\": \"Parameter invalid!\",\n}",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PATDAController.php",
    "groupTitle": "Patda"
  },
  {
    "type": "POST",
    "url": "/patda/penerimaan",
    "title": "Daftar Penerimaan",
    "name": "getDaftarPenerimaan",
    "group": "Patda",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil daftar penerimaan pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "bulan",
            "description": "<p>Bulan pajak PBB. Diisi nomor bulan (1 sampai 12).</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015,\n\t\t\"bulan\" : 6\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/patda/penerimaan",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "data.penerimaan",
            "description": "<p>Dafter penerimaan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.npwpd",
            "description": "<p>Nomor NPWPD.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.nama",
            "description": "<p>Nama toko/wajib pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.jenis",
            "description": "<p>Jenis pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.tahun",
            "description": "<p>Tahun pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.bulan",
            "description": "<p>Bulan pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.total_tagihan",
            "description": "<p>Total tagihan pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.denda",
            "description": "<p>Total denda dari pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.total_bayar",
            "description": "<p>Total pajak yang telah dibayar.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.status",
            "description": "<p>Status pembayaran pajak.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n\t\t\"status\": true,\n\t\t\"message\": \"OK\",\n\t\t\"data\": {\n    \t\t\"penerimaan\": [\n      \t\t{\n          \t\t \"npwpd\": \"P2030097502\",\n \t\t         \"nama\": \"VONY PETRO RASI\",\n \t\t         \"jenis\": \"Hiburan (NonReg)\",\n \t\t         \"tahun\": \"2017\",\n \t\t         \"bulan\": \"00\",\n \t\t         \"total_tagihan\": 83000000,\n \t\t         \"denda\": 0,\n \t\t         \"total_bayar\": 83000000,\n \t\t         \"status\": \"1\"\n \t\t     },\n \t\t     {\n \t\t         \"npwpd\": \"P2030106609\",\n \t\t         \"nama\": \"EMIEL OCTARIA\",\n \t\t         \"jenis\": \"Hiburan (NonReg)\",\n \t\t         \"tahun\": \"2017\",\n \t\t         \"bulan\": \"00\",\n \t\t         \"total_tagihan\": 4360000,\n \t\t         \"denda\": 0,\n \t\t         \"total_bayar\": 4360000,\n \t\t         \"status\": \"1\"\n \t\t     }\n\t\t\t]\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PATDAController.php",
    "groupTitle": "Patda"
  },
  {
    "type": "POST",
    "url": "/patda/penerimaan/jenispajak",
    "title": "Daftar Penerimaan Jenis Pajak",
    "name": "getDaftarPenerimaanJenisPajak",
    "group": "Patda",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil daftar penerimaan jenis pajak pada tahun atau tahun dan bulan tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Jika parameter bulan diisi, maka parameter tahun harus diisi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "bulan",
            "description": "<p>Bulan pajak PBB. Diisi nomor bulan (1 sampai 12).</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "kode_jenis_pajak",
            "description": "<p>Kode jenis pajak.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015,\n\t\t\"bulan\" : 6,\n\t\t\"kode_jenis_pajak\" : \"5\"\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/patda/penerimaan/jenispajak",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "data.penerimaan",
            "description": "<p>Dafter penerimaan.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.jenis_pajak",
            "description": "<p>Jenis pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.total_tagihan",
            "description": "<p>Total tagihan pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.denda",
            "description": "<p>Total denda dari pajak.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "data.penerimaan.total_bayar",
            "description": "<p>Total pajak yang telah dibayar.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n\t\t\"status\": true,\n\t\t\"message\": \"OK\",\n\t\t\"data\": {\n    \t\t\"penerimaan\": [\n      \t\t{\n \t\t         \"jenis_pajak\": \"Hiburan (NonReg)\",\n \t\t         \"total_tagihan\": 83000000,\n \t\t         \"denda\": 0,\n \t\t         \"total_bayar\": 83000000\n \t\t     },\n \t\t     {\n \t\t         \"jenis_pajak\": \"Hiburan (NonReg)\",\n \t\t         \"total_tagihan\": 4360000,\n \t\t         \"denda\": 0,\n \t\t         \"total_bayar\": 4360000\n \t\t     }\n\t\t\t]\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PATDAController.php",
    "groupTitle": "Patda"
  },
  {
    "type": "POST",
    "url": "/patda/penerimaan/target/total",
    "title": "Total Target Penerimaan",
    "name": "getTotalTargetPenerimaan",
    "group": "Patda",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil total target penerimaan pada tahun tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan. Paling lama tahun 2018.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/patda/penerimaan/target/total",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.total_target_penerimaan",
            "description": "<p>Total target penerimaan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n\t\t\"status\": true,\n\t\t\"message\": \"OK\",\n\t\t\"data\": {\n    \t\t\"total_target_penerimaan\": 0\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PATDAController.php",
    "groupTitle": "Patda"
  },
  {
    "type": "POST",
    "url": "/patda/tunggakan/total",
    "title": "Total Tunggakan",
    "name": "getTotalTunggakan",
    "group": "Patda",
    "version": "0.1.1",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengambil total tunggakan pada tahun tertentu, sesuai parameter yang dimasukkan.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "tahun",
            "description": "<p>Tahun pajak. Format: yyyy. Jika tidak diisi, secara default akan menggunakan tahun berjalan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"tahun\" : 2015\n}",
          "type": "json"
        }
      ]
    },
    "examples": [
      {
        "title": "Contoh Penggunaan:",
        "content": "http://119.252.160.220/vtax-web-service/patda/tunggakan/total",
        "type": "json"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status dari eksekusi endpoint, apakah berhasil (true) atau gagal (false).</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Pesan dari hasil eksekusi endpoint.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "data",
            "description": "<p>Semua data yang diperoleh ditampilkan di sini.</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "data.total_tunggakan",
            "description": "<p>Total tunggakan.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n\t\t\"status\": true,\n\t\t\"message\": \"OK\",\n\t\t\"data\": {\n    \t\t\"total_tunggakan\": 0\n\t\t}\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "  HTTP/1.1 200 OK\n  {\n      \"success\": false,\n      \"message\": \"Token invalid : SignatureInvalid - Signature verification failed\"\n\t\t \"data\" : null\n  }",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/PATDAController.php",
    "groupTitle": "Patda"
  },
  {
    "type": "post",
    "url": "/data/alarm",
    "title": "Send Alarm",
    "name": "PostAlarm",
    "group": "Surveillance",
    "version": "0.1.2",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengirimkan data alarm device (tapbox).</p>",
    "examples": [
      {
        "title": "Contoh Penggunaan :",
        "content": "http://119.252.160.220/vtax-web-service/data/alarm",
        "type": "json"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Token",
            "description": "<p>Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "DeviceId",
            "description": "<p>Kode/Nomor device (tapbox).</p>"
          },
          {
            "group": "Parameter",
            "type": "DateTime",
            "optional": false,
            "field": "ServerTimeStamp",
            "description": "<p>Waktu server (yyyy-mm-dd H:i:s).</p>"
          },
          {
            "group": "Parameter",
            "type": "DateTime",
            "optional": false,
            "field": "DeviceTimeStamp",
            "description": "<p>Waktu device (yyyy-mm-dd H:i:s).</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "AlarmCode",
            "description": "<p>Kode alarm.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"Token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n  \t\"DeviceId\" : \"dv001\",\n  \t\"ServerTimeStamp\" : \"2019-04-20 10:10:10\",\n\t\t\"DeviceTimeStamp\" : \"2019-04-20 11:11:11\",\n\t\t\"AlarmCode\" : \"1\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status response, jika berhasil bernilai true dan jika gagal bernilai false.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Deskripsi response.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Respon: ",
          "content": "{\n      \"status\": true,\n      \"message\": \"Insert Success!\"\n }",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Parameter-Invalid",
            "description": "<p>Parameter tidak sesuai.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Token-Invalid",
            "description": "<p>Token salah, expired, dll.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Insert-Failed",
            "description": "<p>Gagal insert data.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response (contoh):",
          "content": "{\n     \"status\": false,\n     \"message\": \"Parameter invalid!\",\n}",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/SurveillanceController.php",
    "groupTitle": "Surveillance"
  },
  {
    "type": "post",
    "url": "/data/transaction",
    "title": "Send Transaction",
    "name": "PostTransaction",
    "group": "Surveillance",
    "version": "0.1.2",
    "permission": [
      {
        "name": "public"
      }
    ],
    "description": "<p>Mengirimkan data transaksi.</p>",
    "examples": [
      {
        "title": "Contoh Penggunaan :",
        "content": "http://119.252.160.220/vtax-web-service/data/transaction",
        "type": "json"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Token",
            "description": "<p>Kode autentikasi.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "NPWPD",
            "description": "<p>NPWPD.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "NOP",
            "description": "<p>NOP.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "TransactionType",
            "description": "<p>[0] TransctionAmount sudah termasuk pajak. <br/>[1] TransactionAmount belum termasuk pajak.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "TaxType",
            "description": "<p>Jenis Pajak <br/> [4] Hotel.<br/> [5] Restoran.<br/> [6] Hiburan.<br/> [7] Reklame.<br/> [8] Penerangan Jalan.<br/> [9] Mineral Non Logam dan Batuan 10 = Parkir.<br/> [11] Air Bawah Tanah.<br/> [12] Sarang Burung Walet.<br/> [24] Hotel (NonReg).<br/> [25] Restoran (NonReg).<br/> [26] Hiburan (NonReg).<br/> [27] Reklame (NonReg).<br/> [28] Penerangan Jalan (NonReg).<br/> [29] Mineral Non Logam dan Batuan (NonReg).<br/> [30] Parkir (NonReg).<br/> [31] Air Bawah Tanah (NonReg).<br/> [32] Sarang Burung Walet (NonReg)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "BranchCode",
            "description": "<p>Branch Code.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "BillNumber",
            "description": "<p>Nomor bill/struk.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "Quantity",
            "description": "<p>Quantity.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Invoice",
            "description": "<p>Invoice.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "TransactionSource",
            "description": "<p>[1] Tapbox. <br/> [2] Cash Register. <br/> [3] File Transfer. <br/> [4] Manual. <br/> [5] Mobile POS. <br/> [6] Web Service. <br/> [7] Tap Server. <br/></p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "TransactionDescription",
            "description": "<p>Deskripsi transaksi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "TransactinAmount",
            "description": "<p>Nilai transaksi.</p>"
          },
          {
            "group": "Parameter",
            "type": "Date",
            "optional": false,
            "field": "TransactionDate",
            "description": "<p>Tanggal transaksi (yyyy-mm-dd H:i:s).</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "Tax",
            "description": "<p>Nilai persentasi pajak.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "DeviceId",
            "description": "<p>Kode/Nomor device (tapbox).</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request Body:",
          "content": "{\n\t\t\"Token\" : \"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ2dGF4LW1iYSIsImF1ZCI6Im1pdHJhLXZ0YXgiLCJpYXQiOjE1NzcwODY5MzMsIm5iZiI6MTU3NzA4Njk0MywiZXhwIjoxNTc3MDkwNTMzLCJkYXRhIjpbXX0.UROhpjq0Z8gIzC0eSDBLNEmehn74ZZ9n515Gv5Hoi5c\",\t\n\t\t\"NPWPD\" : \"PTES001\",\n\t\t\"NOP\" : \"0989098\",\n\t\t\"TaxType\" : 8,\n\t\t\"TransactionType\" : 1,\n\t\t\"BranchCode\" : \"2\",\n\t\t\"BillNumber\" : \"15544372669314619838\",\n\t\t\"Quantity\" : 2,\n\t\t\"Invoice\" : \"15544372669314619838\",\n\t\t\"TransactionDescription\" : \"kosong\", \n\t\t\"TransactionDate\" : \"2019-04-20 11:07:46\",\n\t\t\"TransactionAmount\" : 169500,\n\t\t\"Tax\" : \"13%\",\n\t\t\"DeviceId\" : \"dv001\",\n\t\t\"TransactionSource\" : 5\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Status response, jika berhasil bernilai true dan jika gagal bernilai false.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Deskripsi response.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Respon: ",
          "content": "{\n      \"status\": true,\n      \"message\": \"Insert Success!\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Parameter-Invalid",
            "description": "<p>Parameter tidak sesuai.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Token-Invalid",
            "description": "<p>Token salah, expired, dll.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "TransactionType-Ivalid",
            "description": "<p>TransactionType tidak sesuai.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "TaxType-Invalid",
            "description": "<p>TaxType tidak sesuai.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "TransactionSource-Invalid",
            "description": "<p>TransactionSource tidak sesuai.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Insert-Failed",
            "description": "<p>Gagal insert data.</p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "Insert-Duplicated",
            "description": "<p>Data yang dikirim sudah ada.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response (contoh):",
          "content": "{\n     \"status\": false,\n     \"message\": \"Parameter invalid!\",\n}",
          "type": "json"
        }
      ]
    },
    "filename": "D:/Work/VSI/VTax/Source Code/vtax_web_service/app/Controller/SurveillanceController.php",
    "groupTitle": "Surveillance"
  }
] });
