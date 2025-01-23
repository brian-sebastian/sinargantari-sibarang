<?php

if (!function_exists('template_message')) {

    function template_message($template, $options = [])
    {

        $message = "";

        switch ($template) {

            case "1":

                $no                     = 1;
                $nama                   = $options["nama_customer"];
                $instansi               = $options["settings"]["instansi"];
                $kode_order             = $options["kode_order"];
                $kode_order_enkripsi    = $options["kode_order_enkripsi"];
                $orders                 = $options["orders"];
                $greeting               = greeting();

                $message    .= "Halo $nama selamat $greeting, Terima kasih telah melakukan pemesanan di $instansi\n"
                    . "dengan senang hati kami menginformasikan bahwa kami telah mengkonfirmasi pesanan anda\n\n"
                    . "Kode order : $kode_order\n";

                foreach ($orders as $o) {

                    $message .= $no . ". " . $o["nama_barang"] . " - " . $o["qty"] . "x\n";
                    $no++;
                }

                $message    .= "\nuntuk melakukan pembayaran berikut anda dapat mengakses invoice melalui tautan di bawah ini\n\n"
                    . site_url('tagihan/' . $kode_order_enkripsi) . "\n\n"
                    . "Salam,\n"
                    . $instansi;

                return $message;

                // --> konfirmasi pesanan

                /*
                    Halo [Nama pelanggan] selamat greeting(), Terima kasih telah melakukan pemesanan di [Nama perusahaan]\n
                    dengan senang hati kami menginformasikan bahwa kami telah mengkonfirmasi pesanan anda\n\n

                    kode order : [Kode order]\n
                    [Rincian barang] \n\n
                    
                    untuk melakukan pembayaran berikut anda dapat mengakses invoice melalui tautan di bawah ini\n
                    www.arumqr.com/invoice/QRS==  \n\n
                    Salam,\n
                    [Nama perusahaan]
                */
                break;

            case "2":

                $no                     = 1;
                $nama                   = $options["nama_customer"];
                $instansi               = $options["settings"]["instansi"];
                $kode_order             = $options["kode_order"];
                $kode_order_enkripsi    = $options["kode_order_enkripsi"];
                $orders                 = $options["orders"];
                $greeting               = greeting();

                $message = "Halo $nama selamat $greeting,\n"
                    . "Pembayaran dengan kode order $kode_order telah kami terima \n"
                    . "proses selanjutnya, kami akan melakukan pengemasan pada pemesanan anda \n"
                    . "berikut rincian pesanan :\n\n";

                foreach ($orders as $o) {

                    $message .= $no . ". " . $o["nama_barang"] . " - " . $o["qty"] . "x\n";
                    $no++;
                }

                $message .= "struk bisa anda akses melalui tautan dibawah ini \n\n"
                    . site_url('struk/' . $kode_order_enkripsi) . "\n\n"
                    . "Sekali lagi terima kasih telah memilih $instansi untuk pembelian anda.\n"
                    . "Salam,\n"
                    . $instansi;

                return $message;

                // 

                /*
                    Halo [Nama pelanggan] selamat greeting(),\n
                    Pembayaran dengan kode order [Kode order] telah kami terima \n
                    proses selanjutnya, kami akan melakukan pengemasan pada pemesanan anda \n
                    berikut rincian pesanan : 

                    [Rincian barang] \n\n
                    
                    struk bisa anda akses melalui tautan dibawah ini \n
                    www.arumqr.com/struk/QRS==  \n\n

                    Sekali lagi terima kasih telah memilih [Nama perusahaan] untuk pembelian anda.

                    Salam,\n
                    [Nama perusahaan]

                */
                break;

            case "3":

                $nama                   = $options["nama_customer"];
                $instansi               = $options["settings"]["instansi"];
                $kode_order             = $options["kode_order"];
                $greeting               = greeting();

                $message .= "Halo $nama selamat $greeting, \n\n"
                    . "Kabar baik! pesanan anda sedang dalam pengiriman.\n\n"
                    . "Notifikasi ini untuk memberitahu anda bahwa pesanan anda $kode_order telah berhasil dikirim.\n"
                    . "mohon tunggu untuk informasi selanjutnya.\n\n"
                    . "Salam,\n"
                    . $instansi;

                return $message;

                // 

                /*
                        Halo [Nama pelanggan] selamat greeting(), \n\n

                        Kabar baik! pesanan anda sedang dalam pengiriman.\n\n

                        Notifikasi ini untuk memberitahu anda bahwa pesanan anda [Kode order] telah berhasil dikirim.\n
                        mohon tunggu untuk informasi selanjutnya.\n\n

                        Salam,\n
                        [Nama perusahaan]
                        

                */
                break;

            case "4":

                $nama                   = $options["nama_customer"];
                $instansi               = $options["settings"]["instansi"];
                $email                  = $options["settings"]["email_instansi"];
                $whatsapp               = $options["settings"]["wa_admin"];
                $alamat                 = $options["alamat"];
                $waktu_terima           = $options["waktu"];
                $kode_order             = $options["kode_order"];
                $greeting               = greeting();

                $message = "Halo $nama selamat $greeting, \n\n"
                    . "Paket anda dengan kode order $kode_order telah dikirim ke $alamat pada waktu $waktu_terima\n\n"
                    . "Sekali lagi terima kasih telah memilih kami untuk pembelian anda.\n"
                    . "Jika anda memiliki pertanyaan atau komentar tentang produk anda, gunakan nomor atau email di bawah ini\n\n"
                    . "Email : $email\n"
                    . "Whatsapp : $whatsapp\n"
                    . "untuk menghubungi tim dukungan kami.\n\n"
                    . "Salam, \n"
                    . $instansi;

                return $message;

                // 

                /*
                    Halo [Nama pelanggan] selamat greeting(), \n\n
                    Paket anda telah dikirim ke [Alamat] pada waktu [Waktu kirim]\n\n

                    Sekali lagi terima kasih telah memilih kami untuk pembelian anda. /n
                    Jika anda memiliki pertanyaan atau komentar tentang produk anda, gunakan nomor atau email di bawah ini/n/n
                    Email : [Email]
                    Whatsapp : [Whatsapp]\n
                    untuk menghubungi tim dukungan kami.\n\n

                    Salam, \n


                */
                break;

            case "99":

                $nama                   = $options["nama_customer"];
                $instansi               = $options["settings"]["instansi"];
                $email                  = $options["settings"]["email_instansi"];
                $whatsapp               = $options["settings"]["wa_admin"];
                $kode_order             = $options["kode_order"];
                $greeting               = greeting();

                $message .= "Halo $nama selamat $greeting, \n\n"
                    . "Kami telah menerima permintaan pembatalan pemesanan anda.\n\n"

                    . "Kami mengirimkan notifikasi ini untuk memberitahu anda bahwa kami telah \n"
                    . "membatalkan pesanan anda dengan kode oder $kode_order.\n\n"

                    . "Anda tidak perlu melakukan tindakan lebih lanjut. \n\n"

                    . "Namun, jika anda memiliki pertanyaan lebih lanjut, silahkan hubungi whatsapp atau email dibawah ini :\n"
                    . "Email : $email \n"
                    . "Whatsapp : $whatsapp \n\n"

                    . "Salam, \n"
                    . $instansi;

                return $message;

                /*
                    Halo [Nama pelanggan] selamat greeting(), \n\n

                    Kami telah menerima permintaan pembatalan pemesanan anda.\n\n

                    Kami megnirimkan notifikasi ini untuk memberitahu anda bahwa kami telah \n
                    membatalkan pesanan anda dengan kode oder [Kode order].\n\n

                    Anda tidak perlu melakukan tindakan lebih lanjut. \n\n

                    Namun, jika anda memiliki pertanyaan lebih lanjut, silahkan hubungi whatsapp atau email dibawah ini :\n
                    Email : [email] \n
                    Whatsapp : [Whatsapp] \n\n

                    Salam, \n
                    [Nama perusahaan]
                    
                */
                break;
        }
    }
}
