#**TAMBAH TENDER BARU**#

Gambar di bawah adalah ringkasan gambarajah menunjukkan fungsi-fungsi yang dibenarkan mengikut peranan. Seorang pengguna di dalam sistem ini boleh mempunyai lebih dari satu peranan.

![](/docs/public/content/images/pengesahan/role.png)

Klik pada ikon ![](/docs/public/content/images/tender/icon_magik.png) di menu sebelah kiri untuk membuka senarai menu tegak di sebelah kiri. Ikon ini hanya dipaparkan kepada pengguna agensi sahaja.

Anda akan nampak menu 'Pengurusan Tender' di sebelah kiri halaman. Klik pada 'Pengurusan Tender'. Sistem akan paparkan senarai sub-menu. Klik pada 'Senarai Tender' untuk membuka halaman senarai tender.

![](/docs/public/content/images/tender/menu_tegak.png)

Sistem akan memaparkan senarai tender mengikut agensi pengguna tersebut secara *default* seperti gambar di bawah ini.

    Walaubagaimanapun,jika pengguna mengakses senarai tender dari Menu 'Agensi' yang berada di atas sebelah kanan, pengguna agensi boleh nampak senarai tender daripada agensi lain juga tetapi sistem hanya memberi kebenaran untuk 'Papar' sahaja.

Klik pada butang 'Tambah Tender/Sebut Harga' untuk membuka halaman borang tambah tender baru.

![](/docs/public/content/images/tender/senarai_tender.png)

##**MAKLUMAT TENDER**##

Sistem akan memaparkan borang menambah tender baru. Berikut adalah perincian maklumat untuk borang ini :-

![](/docs/public/content/images/tender/maklumat_tender.png)

**1.Jenis :** Tandakan satu sahaja (Tender ATAU Sebut Harga)

**2.No Rujukan :** pada sistem lama ialah 'Nombor Tender'

**3.Tajuk :** Tajuk tender

**4.Harga Dokumen :** Harga Dokumen yang perlu syarikat bayar.

**5.Tarikh Iklan :** Tarikh Iklan ialah tarikh mula tender/sebut harga diiklan di surat khabar. (sistem akan memaparkan tender di laman web kepada umum jika tarikh mula iklan SUDAH sampai DAN sudah di set 'SIAR' di dalam sistem)

**6.Tarikh Jual :** Tarikh ini ialah tarikh yang akan menentukan bila tender/sebut harga ini boleh dibeli secara online/offline. Tender akan dibenarkan dibeli oleh syarikat jika Tarikh Jual sudah sampai dan di set 'SIAR'. Jika tender/sebut harga di set sebagai 'FUNGSI IKLAN', maka sistem tidak akan memaparkan langsung butang 'Tambah pada senarai tempahan' untuk tidak membenarkan syarikat membeli tender secara online. Maklumat lanjut untuk [FUNGSI IKLAN](/manuals/fungsi_iklan).

**7.Tarikh Tutup :** Apabila mencapai tarikh ini, sistem akan secara automatik tidak membenarkan pembelian tender tersebut secara online. Tender/Sebut Harga tersebut akan bertukar status kepada 'Belum Umum Carta Tender' untuk agensi memasukkan harga yang di hantar oleh syarikat.

    Sila pastikan anda memasukkan tarikh-tarikh di atas ini dengan betul supaya proses pembelian dan penutupan tender berjalan dengan betul.Di bawah ialah notifikasi jika tarikh yang dimasukkan salah. Klik pada butang ber ikon jam dinding di bawah kalender untuk memilih masa.

![](/docs/public/content/images/tender/jam.png)

![](/docs/public/content/images/tender/tarikh_salah.png)

**8.Alamat Serahan :** pada sistem lama ialah Tempat Hantar

**9.Alamat Taklimat:** ialah Alamat tempat taklimat tender/sebut harga diadakan

**10.Tarikh dan Masa Taklimat :** Tarikh dan Masa Taklimat yang diadakan di 'Alamat Taklimat' di atas.

**11.Wajib Hadir Taklimat :** Jika kotak ini ditanda, maka sistem akan tidak membenarkan syarikat layak untuk tender tersebut membeli secara online selagi syarikat tersebut tidak hadir taklimat. Maklumat lanjut tentang [TAKLIMAT WAJIB](/manuals/taklimat_wajib).

Setelah selesai,klik butang 'Seterusnya' untuk pergi ke tab 'Syarat tender'.

##**SYARAT TENDER**##

![](/docs/public/content/images/tender/syarat_tender.png)

Setelah selesai,klik butang 'Seterusnya' untuk pergi ke tab 'Syarat Khas'.

##**SYARAT KHAS**##

Berikut adalah perincian maklumat untuk borang ini :-

**1.Bumiputera Sahaja :** Tanda pada kotak ini jika hanya ingin syarikat berstatus Bumiputra sahaja yang boleh membeli tender secara online. Sistem akan memaparkan notifikasi untuk menyekat syarikat daripada membeli tender secara online.

**2.Syarikat Selangor Sahaja :** Tanda pada kotak ini jika hanya ingin syarikat beralamat di dalam Selangor sahaja yang boleh membeli tender secara online. Sistem akan memaparkan notifikasi untuk menyekat syarikat daripada membeli tender secara online.

**3.Syarikat Daerah :** Pilih daerah jika hanya ingin syarikat beralamat di daerah tersebut sahaja yang boleh membeli tender secara online. Sistem akan memaparkan notifikasi untuk menyekat syarikat daripada membeli tender secara online.

    Jika ada keperluan mengemaskini input ini daripa ada daerah kepada tiada daerah, sila klik pada hujung perkataan daerah di dalam kotak input ini kemudian tekan kekunci 'DELETE'/'BACKSPACE' pada papan kekunci anda.

**4.Tender Terhad :** Tanda pada kotak tetapan ini untuk membenarkan hanya syarikat yang dijemput  sahaja dapat membeli dokumen tender. Tender terhad tidak akan dipapar di laman web walaupun sudah di set 'SIAR'. Maklumat lanjut tentang [TENDER TERHAD](/manuals/tender_terhad).

**5.Fungsi Iklan :** Tandakan pada kotak ini jika ingin pembelian tender/sebut harga dibuat secara manual (syarikat hanya dapat membeli dokumen tender tersebut di tempat yang dinyatakan di dalam 'Syarat Tender'. Perkara ini adalah untuk agensi yang tidak mempunyai tetapan _payment gateway_ di dalam sistem ini dan juga jika memang pembelian diamalkan secara manual/offline).

    Peringatan untuk 'Fungsi Iklan' ini, sistem tidak akan dapat menyaring kelayakan syarikat terhadap tender justeru tidak dapat menghalang pembelian dokumen tender jika syarikat tidak layak.

![](/docs/public/content/images/tender/syarat_khas.png)

Setelah selesai,klik butang 'Seterusnya' untuk pergi ke tab 'Lawatan Tapak'.

##**LAWATAN TAPAK**##

Lawatan tapak mempunyai tetapan kehadiran wajib atau tidak wajib. Jika kotak 'Wajib' ini ditanda, maka sistem tidak membenarkan syarikat layak untuk tender tersebut membeli secara online selagi syarikat tersebut tidak hadir lawatan tapak yang di set 'WAJIB'. Maklumat lanjut tentang [LAWATAN TAPAK WAJIB](/manuals/lawatan_tapak). Lawatan tapak ini tiada had. Klik butang 'Tambah' untuk menambah lawatan.

![](/docs/public/content/images/tender/tapak.png)

Setelah selesai,klik butang 'Seterusnya' untuk pergi ke tab 'Kod-Kod Bidang'.

##**KOD - KOD BIDANG**##

Masukkan kod-kod bidang untuk menepati syarat-syarat tender seperti contoh gambar di bawah.

![](/docs/public/content/images/tender/kod.png)

Setelah selesai,klik butang 'Seterusnya' untuk pergi ke tab 'Dokumen'.

##**DOKUMEN TENDER/SEBUT HARGA DAN DOKUME MEJA TERKAWAL**##

Dokumen mempunyai 2 jenis iaitu :-

![](/docs/public/content/images/tender/upload.png)

**1. Dokumen Tender :** Secara *default* dokumen yang tidak ditanda pada kotak 'Dokumen Meja' adalah dianggap sebagai dokumen tender yang harus dibeli secara online atau offline. Berikut adalah senario yang boleh terjadi pada dokumen tender. Sistem membenarkan pelbagai jenis dokumen dimuatnaik. (*.pdf,*doc,*xls dan sebagainya) .

Untuk melihat paparan dokumen tender yang dapat dilihat oleh syarikat sila klik pada pautan ini [PAPARAN DOKUMEN TENDER UNTUK SYARIKAT](/manuals/dok_tender)

    SENARIO 1 : Dokumen tender boleh dibeli secara online dan dimuat turun juga secara online. Apabila syarikat berjaya membeli tender secara online, barulah syarikat akan nampak senarai dokumen tender yang boleh dimuat turun di tab 'DOKUMEN TENDER'.

    SENARIO 2 : Terdapat juga senario di mana dokumen tender haruslah diambil di kaunter agensi. Untuk senario ini, syarikat akan membeli tender secara online dan akan membawa resit yang ada dicetak dengan no siri dokumen ke kaunter agensi untuk mengambil dokumen. Untuk senario ini, agensi tidak perlu memuat naik dokumen tender TETAPI harus menyatakan prosedur pembayaran secara online tersebut dan mengambil dokumen tender di kaunter agensi di dalam tab 'SYARAT TENDER'. Jika syarikat berjaya membeli tender secara online dan membuka halaman tender ini, sistem akan memaparkan notifikasi pada tab 'DOKUMEN TENDER' :'Tiada fail untuk dimuat turun, sila rujuk syarat tender atau berhubung dengan agensi yang berkenaan.'.

    -SENARIO 3 :Jika 'FUNGSI IKLAN' di set pada tender/sebut harga ini,agensi tidak perlu memuat naik dokumen tender kerana tiada pembelian online boleh dilakukan ke atas tender tersebut.Untuk senario ini, agensi tidak perlu memuat naik dokumen tender TETAPI harus menyatakan prosedur pembayaran secara manual dan mengambil dokumen tender di kaunter agensi di dalam tab 'SYARAT TENDER'. Untuk dokumen ini juga, agensi akan memberi no siri dokumen secara manual juga.

**2. Dokumen Meja :** Secara *default* dokumen yang ditanda pada kotak 'Dokumen Meja' adalah dianggap sebagai dokumen meja yang TIADA perlu melakukan pembayaran (percuma) secara online atau offline. Dokumen tersebut akan dipaparkan dan boleh dimuat turun pada bila-bila masa. Dokumen ini biasanya adalah bertujuan untuk *preview* dokumen. Dokumen ini tidak mempunyai no siri dan juga akan diletak *watermark* apabilan agensi memuat naik dokumen ini. Sistem hanya akan membenarkan dokumen berjenis pdf sahaja untuk dimuat naik.

Untuk melihat paparan dokumen meja yang dapat dilihat oleh syarikat sila klik pada pautan ini [PAPARAN DOKUMEN MEJA UNTUK SYARIKAT](/manuals/dok_meja)

Setelah selesai, klik butang 'Hantar' untuk menyimpan maklumat tender.

##**CONTOH TENDER**##

Berikut merupakan contoh tender yang telah siap di simpan berdasarkan langkah di atas mengikut tab masing-masing.

Tab 'Maklumat Tender'.

![](/docs/public/content/images/tender/tab_maklumat_tender.png)

Tab 'Syarat Tender'.

![](/docs/public/content/images/tender/tab_syarat_tender.png)

Tab 'Lawatan Tapak'.

![](/docs/public/content/images/tender/tab_lawat_tapak.png)

Tab 'Kod-Kod Bidang'.

![](/docs/public/content/images/tender/tab_kod_bidang.png)

Tab 'Dokumen'.

![](/docs/public/content/images/tender/dokumen.png)

Untuk melihat sejarah perubahan, agensi boleh klik pada tab 'Sejarah Pengubahan'.

![](/docs/public/content/images/tender/sejarah.png)

Setelah tender siap di buat, agensi boleh mula menyiarkan tender. Maklumat lanjut tentang [SIAR/BATAL SIAR TENDER](/manuals/siar)

















