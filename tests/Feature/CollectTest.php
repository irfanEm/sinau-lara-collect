<?php

namespace Tests\Feature;

use App\Data\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CollectTest extends TestCase
{
    public function testBuatCollection()
    {
        $collect = collect([1, 3, 4, 5]);
        self::assertEqualsCanonicalizing([1, 3, 4, 5], $collect->all());
    }

    public function testForeach()
    {
        $collection = collect([1,2,3,4,5,6,7,8,9]);

        foreach($collection as $key => $value){
            self::assertEquals($key + 1, $value);
        }
    } 

    public function testManipulasiCollect()
    {
        $collection = collect([]);
        $collection -> push(1, 2, 3, 4, 5);

        self::assertEqualsCanonicalizing([1, 2, 3, 4, 5], $collection->all());

        $hasil = $collection -> pop();

        self::assertEquals(5, $hasil);

        self::assertEqualsCanonicalizing([1, 2, 3, 4], $collection->all());
    }

    public function testMap()
    {
        $koleksi = collect([2, 4, 6]);
        $hasil = $koleksi->map(function($item){
            return $item * 9;
        });

        self::assertEqualsCanonicalizing([18,36,54], $hasil->all());
    }

    public function testMapInto()
    {
        $koleksi = collect(["balqis", "farah", "anabila"]);
        $hasil = $koleksi->mapInto(Person::class);
        
        self::assertEquals([new Person("balqis"), new Person("farah"), new Person("anabila")], $hasil->all());
    }

    public function testMapSpread()
    {
        $koleksi = collect([
            ["Balqis", "Farah"], 
            ["Shilvia", "Qurrota"], 
            ["Irfan", "Machmud"]
        ]);

        $hasil = $koleksi->mapSpread(function($namaDepan, $namaBelakang){
            $namaLengkap = $namaDepan . " - " . $namaBelakang;
            return new Person($namaLengkap);
        });

        self::assertEquals([
            new Person("Balqis - Farah"),
            new Person("Shilvia - Qurrota"),
            new Person("Irfan - Machmud")
        ], $hasil->all());
    }

    public function testMapToGroup()
    {
        $koleksi = collect([
            [
                'nama' => 'Andi',
                'alamat' => 'Jl. Merdeka No. 123',
                'umur' => 25,
                'jenis_kelamin' => 'Laki-laki'
            ],
            [
                'nama' => 'Budi',
                'alamat' => 'Jl. Pahlawan No. 456',
                'umur' => 30,
                'jenis_kelamin' => 'Laki-laki'
            ],
            [
                'nama' => 'Citra',
                'alamat' => 'Jl. Jendral Sudirman No. 789',
                'umur' => 22,
                'jenis_kelamin' => 'Perempuan'
            ],
            [
                'nama' => 'Dewi',
                'alamat' => 'Jl. Diponegoro No. 101',
                'umur' => 28,
                'jenis_kelamin' => 'Perempuan'
            ],
            [
                'nama' => 'Eko',
                'alamat' => 'Jl. Gatot Subroto No. 555',
                'umur' => 35,
                'jenis_kelamin' => 'Laki-laki'
            ],
            [
                'nama' => 'Fani',
                'alamat' => 'Jl. Imam Bonjol No. 222',
                'umur' => 19,
                'jenis_kelamin' => 'Perempuan'
            ],
            [
                'nama' => 'Gita',
                'alamat' => 'Jl. Sisingamangaraja No. 333',
                'umur' => 24,
                'jenis_kelamin' => 'Perempuan'
            ],
            [
                'nama' => 'Hadi',
                'alamat' => 'Jl. Sudirman No. 777',
                'umur' => 32,
                'jenis_kelamin' => 'Laki-laki'
            ],
            [
                'nama' => 'Indra',
                'alamat' => 'Jl. Hayam Wuruk No. 444',
                'umur' => 27,
                'jenis_kelamin' => 'Laki-laki'
            ],
            [
                'nama' => 'Joko',
                'alamat' => 'Jl. Ahmad Yani No. 666',
                'umur' => 29,
                'jenis_kelamin' => 'Laki-laki'
            ]
        ]);

        $hasil = $koleksi->mapToGroups(function($item){
            return [$item["jenis_kelamin"] => $item["nama"]];
        });

        self::assertEquals([
            "Laki-laki" => collect(["Andi", "Budi", "Eko", "Hadi", "Indra", "Joko"]),
            "Perempuan" => collect(["Citra", "Dewi", "Fani", "Gita"])
        ], $hasil->all());
    }

    public function testZip()
    {
        $koleksi1 = collect([1, 2, 3]);
        $koleksi2 = collect([4, 5, 6]);
        $koleksi3 = $koleksi1->zip($koleksi2);

        self::assertEqualsCanonicalizing([
            collect([1, 4]),
            collect([2, 5]),
            collect([3, 6])
        ], $koleksi3->all());
    }

    public function testConcat()
    {
        $koleksi1 = collect([1,2,3]);
        $koleksi2 = collect([4,5,6]);
        $koleksi3 = $koleksi1 -> concat($koleksi2);

        self::assertEqualsCanonicalizing([1,2,3,4,5,6], $koleksi3->all());
    }

    public function testCombine()
    {
        $koleksi1 = collect(["nama", "umur"]);
        $koleksi2 = collect(["Balqis FA", 3]);
        $koleksi3 = $koleksi1->combine($koleksi2);

        self::assertEqualsCanonicalizing(["nama" => "Balqis FA", "umur" => 3], $koleksi3->all());
    }

    public function testCollapse()
    {
        $koleksi1 = collect([[1,2,3], [4,5,6], [7,8,9]]);

        $hasil = $koleksi1->collapse();

        self::assertEqualsCanonicalizing([1,2,3,4,5,6,7,8,9], $hasil->all());
    }

    public function testFlatMap()
    {
        $koleksi = collect([
            [
                "nama" => "Irfan M",
                "hobi" => ["Coding", "Reading", "Riding"]
            ],
            [
                "nama" => "Shilvia",
                "hobi" => ["Cooking", "Scrolling", "Reading"]
            ]
        ]);

        $hasil = $koleksi->flatMap(function($Item){
            return $Item['hobi'];
        });

        self::assertEqualsCanonicalizing(["Coding","Reading","Riding","Cooking","Scrolling","Reading"], $hasil->all());
    }

    public function testStringRepresentation()
    {
        $koleksi = collect(["Balqis", "Farah", "Anabila"]);
        $hasil = $koleksi->join('-');
        $hasil2 = $koleksi->join('-', '@');
        self::assertEquals("Balqis-Farah-Anabila", $hasil);
        self::assertEquals("Balqis-Farah@Anabila", $hasil2);
    }

    public function testFiltering()
    {
        $koleksi = collect([
            "Balqis" => 99,
            "Farah" => 97, 
            "Anabila" => 95
        ]);

        $hasil = $koleksi->filter(function($item, $key){
            return $item > 95;
        });

        self::assertEquals([
            "Balqis" => 99,
            "Farah" => 97
        ], $hasil->all());
    }

    public function testFilterIndex()
    {
        $koleksi = collect([1,2,3,4,5,6,7,8,9,10]);
        $hasil = $koleksi->filter(function($nilai, $hasil){
            return $nilai % 2 == 0;
        });

        self::assertEqualsCanonicalizing([2,4,6,8,10], $hasil->all());
    }
}
