<?php

namespace Database\Factories;

use App\Models\Koleksi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

class KoleksiFactory extends Factory
{
    protected $model = Koleksi::class;

    public function definition(): array
    {
        $statusOptions = ['tidak', 'sewa', 'beli', 'sewa_beli'];
        $lokasiOptions = ['dipamerkan', 'disimpan'];
        $techniques = [
            'Akrilik di atas kanvas',
            'Minyak di atas kanvas',
            'Tinta dan kertas tradisional',
            'Lukisan cat air',
            'Grafis cetak',
            'Keramik glasir',
            'Mixed media',
        ];

        $paintingNames = [
            'Senja di Bukit Timur',
            'Potret Ibu Rumah Tangga',
            'Gelombang Pantai Utama',
            'Alam Batin Manusia',
            'Perjalanan Sang Pejalan',
            'Mimpi di Tengah Malam',
            'Warna-Warni Kehidupan',
            'Cerminan Jiwa',
            'Langit Berbintang',
            'Ketenangan dalam Kesedihan',
            'Jejak Masa Lalu',
            'Harapan Baru',
            'Keindahan Alam Tropis',
            'Tarian Cahaya',
            'Musim Berganti',
            'Sepi Indah',
            'Harmoni Warna',
            'Kenangan Indah',
            'Kebangkitan Pagi',
            'Pertemuan Dua Dunia',
            'Rumah Impian',
            'Kisah Tak Terucapkan',
            'Kebebasan',
            'Kasih Sayang Ibu',
            'Keberanian Menghadap',
            'Persimpangan Jalan',
            'Harapan Terakhir',
            'Terang Dalam Gelap',
            'Cinta Sejati',
            'Petualangan Dimulai',
        ];

        $paintingDescriptions = [
            'Lukisan pemandangan senja di perbukitan timur dengan gradasi oranye dan ungu yang lembut. Karya ini menangkap momen transisi siang ke malam di pedesaan Indonesia.',
            'Potret seorang ibu rumah tangga dalam suasana domestik hangat, dengan teknik sapuan halus yang menonjolkan ekspresi penuh kasih sayang dan kelelahan sehari-hari.',
            'Komposisi abstrak gelombang laut dengan palet biru dan putih yang dinamis. Menggambarkan kekuatan dan irama alam pantai utara Nusantara.',
            'Karya figuratif yang mengeksplorasi hubungan manusia dengan alam sekitar melalui simbol-simbol organik dan warna tanah yang kaya.',
            'Lukisan jalan setapak yang membentang ke cakrawala, melambangkan perjalanan hidup dan pencarian makna di tengah perubahan zaman.',
            'Surrealisme malam hari dengan bayangan lembut dan cahaya bulan yang menembus awan. Nuansa tenang sekaligus penuh misteri.',
            'Komposisi warna-warni cerah yang merayakan dinamika kehidupan sehari-hari, dari pasar tradisional hingga interaksi sosial masyarakat.',
            'Potret introspektif dengan permainan cahaya dan bayangan yang menggambarkan refleksi batin sang subjek.',
            'Pemandangan langit malam berbintang di atas sawah, dengan teknik pointillisme ringan yang memberi kesan berkilau.',
            'Lukisan emosional dengan palet redup dan sapuan tenang, menangkap perpaduan antara duka dan ketenangan batin.',
            'Kanvas nostalgia yang menggambarkan artefak dan ruang lama, mengajak penikmat seni merenungkan jejak masa lampau.',
            'Komposisi penuh cahaya dengan simbol-simbol pertumbuhan dan awal baru, menggunakan warna hijau dan kuning cerah.',
            'Pemandangan hutan tropis dengan keanekaragaman flora yang kaya, merekam keindahan alam Indonesia yang masih asri.',
            'Abstraksi gerak tarian tradisional melalui garis dinamis dan percikan warna, mengekspresikan energi budaya lokal.',
            'Lukisan empat musim dalam satu kanvas, menunjukkan perubahan warna alam dari kemarau hingga penghujan.',
            'Suasana sepi yang indah di sudut kota tua, dengan arsitektur kolonial dan jalanan sunyi yang penuh karakter.',
            'Eksplorasi harmoni warna komplementer dalam komposisi geometris dan organik yang seimbang.',
            'Lukisan kenangan masa kecil di desa, dengan elemen-elemen seperti pohon rindang, sungai kecil, dan rumah panggung.',
            'Pemandangan fajar dengan sinar matahari pertama menyentuh permukaan air, simbol harapan dan permulaan.',
            'Karya konseptual tentang pertemuan tradisi dan modernitas, digambarkan melalui kontras arsitektur dan gaya hidup.',
            'Lukisan rumah idaman di pedesaan dengan taman bunga dan langit cerah, penuh nuansa nostalgia dan damai.',
            'Komposisi figuratif tanpa wajah jelas, mengundang penafsir sendiri tentang cerita yang tersembunyi di balik kanvas.',
            'Simbol kebebasan melalui burung-burung terbang di langit terbuka, dengan sapuan kuas yang ringan dan penuh gerak.',
            'Potret ibu dan anak dalam momen kebersamaan sederhana, menonjolkan ikatan kasih sayang yang universal.',
            'Figur manusia berdiri tegak menghadap cahaya, melambangkan keberanian menghadapi tantangan hidup.',
            'Lukisan persimpangan jalan di pedesaan, metafora pilihan hidup dan arah yang harus diambil.',
            'Karya melankolis namun penuh harapan, dengan simbol-simbol kehidupan yang hampir padam namun masih bersinar.',
            'Kontras gelap dan terang yang dramatis, menggambarkan cahaya harapan di tengah kegelapan situasi.',
            'Potret sepasang kekasih dalam suasana romantis, dengan palet warna hangat dan lembut.',
            'Pemandangan awal petualangan: kapal, koper, dan cakrawala luas yang mengundang penjelajahan.',
        ];

        $bookNames = [
            'Naskah Hikayat Raja Muda',
            'Antologi Puisi Modern Indonesia',
            'Sejarah Kerajaan Nusantara',
            'Catatan Perjalanan ke Timur',
            'Filsafat dan Kehidupan',
            'Seni Tradisional Jawa',
            'Kisah Para Pendiri Bangsa',
            'Ensiklopedi Budaya Indonesia',
            'Manuskrip Agama Kuno',
            'Buku Harian Seorang Pejuang',
            'Koleksi Cerita Rakyat',
            'Penelitian tentang Aksara Lama',
            'Memoir Seniman Terkenal',
            'Panduan Seni Melukis Tradisional',
            'Risalah Perdagangan Maritim',
            'Studi Tentang Batik Indonesia',
            'Biografi Tokoh Nasional',
            'Kamus Bahasa Daerah Kuno',
            'Teks Mistis dan Spiritual',
            'Dokumentasi Upacara Adat',
            'Karya Sastra Klasik Melayu',
            'Jurnal Peneliti Arkeologi',
            'Koleksi Lagu Tradisional',
            'Buku Panduan Kerajinan Tangan',
            'Laporan Perjalanan Misi Ilmiah',
            'Tesis Tentang Filosofi Timur',
            'Catatan Medis Tradisional',
            'Peta Kuno Kepulauan Nusantara',
            'Koleksi Puisi Cinta Klasik',
            'Dokumentasi Festival Budaya',
        ];

        $bookDescriptions = [
            'Naskah kuno berisi kisah hikayat raja muda yang berjuang mempertahankan kerajaannya. Ditulis dengan aksara lokal pada kertas daun yang dilestarikan dengan sangat hati-hati.',
            'Kumpulan puisi modern Indonesia dari berbagai penulis terkemuka abad ke-20, merekam perkembangan sastra dan suara generasi pasca-kemerdekaan.',
            'Buku referensi sejarah kerajaan-kerajaan besar di Nusantara, dilengkapi peta dan ilustrasi periode keemasan maritim.',
            'Catatan perjalanan seorang sarjana yang menelusuri rute perdagangan rempah ke Timur, dengan sketsa dan observasi budaya setempat.',
            'Karya filsafat populer yang membahas makna hidup, etika, dan kebijaksanaan dari perspektif Timur dan Barat.',
            'Dokumentasi mendalam tentang seni tradisional Jawa, meliputi wayang, gamelan, batik, dan upacara adat.',
            'Narasi biografis para pendiri bangsa Indonesia, disusun berdasarkan arsip dan surat-menyurat asli masa perjuangan.',
            'Ensiklopedi budaya Indonesia dalam beberapa volume, mencakup adat istiadat, bahasa, seni, dan kepercayaan masyarakat.',
            'Manuskrip agama kuno berisi ajaran spiritual dan ritual kepercayaan leluhur, ditulis tangan pada material pergamen.',
            'Buku harian seorang pejuang kemerdekaan yang merekam pengalaman harian, strategi perjuangan, dan harapan untuk masa depan.',
            'Kompilasi cerita rakyat dari berbagai daerah Nusantara, dilengkapi catatan etnografis dan ilustrasi tradisional.',
            'Penelitian akademik tentang aksara-aksara kuno Nusantara, termasuk metode dekripsi dan konteks historis penggunaannya.',
            'Memoir autobiografi seniman terkenal yang menceritakan perjalanan kreatif, tantangan, dan karya-karya monumentalnya.',
            'Panduan praktis teknik melukis tradisional Indonesia, dari persiapan pigmen alami hingga komposisi karya.',
            'Risalah perdagangan maritim abad ke-17 yang mencatat rute kapal, komoditas, dan hubungan dagang antarpulau.',
            'Studi komprehensif tentang batik Indonesia: sejarah motif, teknik pembuatan, dan makna simbolis setiap pola.',
            'Biografi tokoh nasional yang berpengaruh, disusun berdasarkan wawancara, arsip keluarga, dan dokumen resmi.',
            'Kamus bahasa daerah kuno dengan entri dan arti kata, disertai catatan tentang asal-usul dan perubahan linguistik.',
            'Teks mistis dan spiritual berisi ajaran kebatinan, doa tradisional, dan panduan meditasi dari tradisi lokal.',
            'Dokumentasi lengkap upacara adat dari berbagai suku, termasuk urutan ritual, peralatan, dan makna simbolis.',
            'Karya sastra klasik Melayu dalam edisi facsimile, dengan terjemahan dan catatan kritis para ahli filologi.',
            'Jurnal peneliti arkeologi yang mencatat temuan situs purbakala, metode ekskavasi, dan analisis artefak.',
            'Koleksi lirik lagu tradisional dari berbagai daerah, disertai notasi musik dan catatan konteks pertunjukan.',
            'Buku panduan kerajinan tangan tradisional: anyaman, ukir kayu, tenun, dan teknik pewarnaan alami.',
            'Laporan perjalanan misi ilmiah ke pelosok Nusantara, mencatat flora, fauna, dan kehidupan masyarakat adat.',
            'Tesis filosofi Timur yang membahas konsep diri, karma, dan jalan spiritual menurut tradisi Nusantara.',
            'Catatan medis tradisional berisi resep obat herbal, metode pengobatan, dan pengetahuan kesehatan leluhur.',
            'Facsimile peta kuno Kepulauan Nusantara dengan catatan navigasi, nama pulau historis, dan rute pelayaran.',
            'Antologi puisi cinta klasik dari sastra Melayu dan Indonesia, dengan analisis gaya dan konteks sosial.',
            'Dokumentasi festival budaya tahunan: foto, jadwal acara, dan penjelasan makna setiap ritual pertunjukan.',
        ];

        static $inventorySequenceMap = [];
        static $generationIndex = 0;

        $category = $this->faker->numberBetween(1, 100) <= 80 ? 'lukisan' : 'buku';
        
        if ($category === 'lukisan') {
            $nameIndex = $this->faker->numberBetween(0, count($paintingNames) - 1);
            $name = $paintingNames[$nameIndex];
            $description = $paintingDescriptions[$nameIndex];
        } else {
            $nameIndex = $this->faker->numberBetween(0, count($bookNames) - 1);
            $name = $bookNames[$nameIndex];
            $description = $bookDescriptions[$nameIndex];
        }
        $artifactYear = $category === 'lukisan'
            ? $this->faker->numberBetween(1970, now()->year)
            : $this->faker->numberBetween(1700, now()->year);
        $createdAt = now()->subSeconds($generationIndex++);
        $inventoryYear = $createdAt->format('Y');

        $inventoryKey = sprintf('%s-%s', $category, $inventoryYear);
        if (! isset($inventorySequenceMap[$inventoryKey])) {
            $inventorySequenceMap[$inventoryKey] = Koleksi::getNextSequenceForCategory($category, $inventoryYear) - 1;
        }
        $inventorySequence = ++$inventorySequenceMap[$inventoryKey];

        $size = sprintf('%d x %d cm', $this->faker->numberBetween(25, 120), $this->faker->numberBetween(20, 100));
        $fotoPaths = self::loadKoleksiFotoPaths($category);

        $statusSewa = $this->faker->randomElement($statusOptions);
        $canRent = in_array($statusSewa, ['sewa', 'sewa_beli'], true);
        $canBuy = in_array($statusSewa, ['beli', 'sewa_beli'], true);

        $dailyRate = $canRent
            ? $this->faker->numberBetween(25, 35) * 5000
            : 0;

        $salePrice = $canBuy
            ? ($category === 'lukisan'
                ? $this->faker->numberBetween(195, 750) * 10_000
                : $this->faker->numberBetween(50, 350) * 10_000)
            : null;

        $weightGram = $this->faker->numberBetween(500, 5000);

        return [
            'nama' => $name,
            'kategori' => $category,
            'nomor_inventaris' => Koleksi::generateNomorInventaris($category, $inventoryYear, $inventorySequence),
            'seniman' => $category === 'lukisan'
                ? ($this->faker->numberBetween(1, 100) <= 80 ? 'MK Lesmana' : $this->faker->name())
                : $this->faker->name(),
            'tahun' => (string) $artifactYear,
            'teknik_media' => $category === 'lukisan' ? $this->faker->randomElement($techniques) : null,
            'ukuran_lukisan' => $category === 'lukisan' ? $size : null,
            'deskripsi' => $description,
            'status_sewa' => $statusSewa,
            'lokasi' => $this->faker->randomElement($lokasiOptions),
            'kondisi' => null,
            'foto' => $fotoPaths !== [] ? $this->faker->randomElement($fotoPaths) : null,
            'daily_rate' => $dailyRate,
            'sale_price' => $salePrice,
            'weight_gram' => $weightGram,
            'for_rent' => $canRent,
            'for_sale' => $canBuy,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    /**
     *
     * @return list<string>
     */
    private static function loadKoleksiFotoPaths(string $category): array
    {
        static $pathsByCategory = [];

        if (isset($pathsByCategory[$category])) {
            return $pathsByCategory[$category];
        }

        $folder = ucfirst($category);
        $directory = storage_path('app/public/koleksi/' . $folder);

        if (! File::isDirectory($directory)) {
            return $pathsByCategory[$category] = [];
        }

        return $pathsByCategory[$category] = collect(File::files($directory))
            ->map(fn ($file) => 'koleksi/' . $folder . '/' . $file->getFilename())
            ->values()
            ->all();
    }
}
