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

    public function testPartitioning()
    {
        $koleksi = collect([
            "angga" => 99,
            "budi" => 98, 
            "ceri" => 88,
            "dani" => 99
        ]);

        [$hasil1, $hasil2] = $koleksi->partition(function($item, $key){
            return $item >98;
        });

        self::assertEquals(["angga" => 99, "dani" => 99], $hasil1->all());
        self::assertEquals(["budi" => 98,"ceri" => 88], $hasil2->all());
    }

    public function testTesting()
    {
        $koleksi = collect(["Balqis", "Farah", "Anabila"]);
        
        self::assertTrue($koleksi->has([0,1,2]));
        self::assertTrue($koleksi->hasAny([1,3]));
        self::assertTrue($koleksi->contains("Balqis"));
        self::assertTrue($koleksi->contains(function($item, $key){
            return $item == "Anabila";
        }));
    }

    public function testGrouping()
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

        $hasil = $koleksi->groupBy("jenis_kelamin");

        self::assertEquals([
            "Laki-laki" => collect([
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
                    'nama' => 'Eko',
                    'alamat' => 'Jl. Gatot Subroto No. 555',
                    'umur' => 35,
                    'jenis_kelamin' => 'Laki-laki'
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
            ]),
            "Perempuan" => collect([
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
                ]
            ])
        ], $hasil->all());

        self::assertEquals([
            "Laki-laki" => collect([
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
                    'nama' => 'Eko',
                    'alamat' => 'Jl. Gatot Subroto No. 555',
                    'umur' => 35,
                    'jenis_kelamin' => 'Laki-laki'
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
            ]),
            "Perempuan" => collect([
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
                ]
            ])
        ], $koleksi->groupBy(function($item, $key){
            return $item['jenis_kelamin'];
        })->all());
    }

    public function testSlicing()
    {
        $koleksi = collect([1,2,3,4,5,6,7,8,9]);
        self::assertEqualsCanonicalizing([6,7,8,9], $koleksi->slice(5)->all());
        self::assertEqualsCanonicalizing([3,4,5], $koleksi->slice(2, 3)->all());
    }

    public function testTake()
    {
        $koleksi = collect([1,2,3,4,5,6,7,8,9]);

        $hasilTake = $koleksi->take(3);
        self::assertEqualsCanonicalizing([1,2,3], $hasilTake->all());

        $hasilTakeUntil = $koleksi->takeUntil(function($item, $key){
            return $item == 6;
        });
        self::assertEqualsCanonicalizing([1,2,3,4,5], $hasilTakeUntil->all());

        $hasilTakeWhile = $koleksi->takeWhile(function($value, $key){
            return $value < 9;
        });
        self::assertEqualsCanonicalizing([1,2,3,4,5,6,7,8], $hasilTakeWhile->all());
    }

    public function testSkip()
    {
        $koleksi = collect([1,2,3,4,5,6,7,8,9]);

        $hasilSkip = $koleksi->skip(3);
        self::assertEqualsCanonicalizing([4,5,6,7,8,9], $hasilSkip->all());

        $hasilSkipUntil = $koleksi->skipUntil(function($item, $key){
            return $item == 6;
        });
        self::assertEqualsCanonicalizing([6,7,8,9], $hasilSkipUntil->all());

        $hasilSkipWhile = $koleksi->skipWhile(function($value, $key){
            return $value < 9;
        });
        self::assertEqualsCanonicalizing([9], $hasilSkipWhile->all());
    }

    public function testChunk()
    {
        $koleksi = collect([1,2,3,4,5,6,7,8,9]);
        $hasil = $koleksi->chunk(3);

        self::assertEqualsCanonicalizing([1,2,3], $hasil->all()[0]->all());
        self::assertEqualsCanonicalizing([4,5,6], $hasil->all()[1]->all());
        self::assertEqualsCanonicalizing([7,8,9], $hasil->all()[2]->all());
    }

    public function testRetrieve()
    {
        $koleksi = collect([1,2,3,4,5,6,7,8,9]);
        $hasil = $koleksi->first();
        self::assertEqualsCanonicalizing(1, $hasil);

        $hasil = $koleksi->first(function($value, $key){
            return $value > 8;
        });
        self::assertEqualsCanonicalizing(9, $hasil);
    }

    public function testRetrieveLast()
    {
        $koleksi = collect([1,2,3,4,5,6,7,8,9]);
        $hasil = $koleksi->last();
        self::assertEqualsCanonicalizing(9, $hasil);

        $hasil = $koleksi->last(function($value, $key){
            return $value < 8;
        });
        self::assertEqualsCanonicalizing(7, $hasil);
    }

    public function testRandom()
    {
        $koleksi = collect([1,2,3,4,5,6,7,8,9]);
        $hasilRand = $koleksi->random();
        self::assertTrue(in_array($hasilRand, [1,2,3,4,5,6,7,8,9]));
    }

    public function testCheckingExtension()
    {
        $koleksi = collect([1,2,3,4,5,6,7,8,9]);
        self::assertTrue($koleksi->isNotEmpty());
        self::assertFalse($koleksi->isEmpty());
        self::assertTrue($koleksi->contains(1));
        self::assertTrue($koleksi->contains(function($value, $key){
            return $value == 4;
        }));
        self::assertFalse($koleksi->containsOneItem());
    }

    public function testOrdering()
    {
        $koleksi = collect([1,4,5,6,2,3,7,9,8]);
        $hasilAsc = $koleksi->sort();

        self::assertEqualsCanonicalizing([1,2,3,4,5,6,7,8,9], $hasilAsc->all());

        $hasilDesc = $koleksi->sortDesc();
        self::assertEqualsCanonicalizing([9,8,7,6,5,4,3,2,1], $hasilDesc->all());
    }

    public function testAgregate()
    {
        $koleksi = collect([1,2,3,4,5,6,7,8,9]);
        
        $hasil = $koleksi->min();
        self::assertEquals(1, $hasil);

        $hasilMax = $koleksi->max();
        self::assertEquals(9, $hasilMax);

        $hasilCount = $koleksi->count();
        self::assertEquals(9, $hasilCount);

        $hasilSum = $koleksi -> sum();
        self::assertEquals(45, $hasilSum);
        
        $hasilAvg = $koleksi->avg();
        self::assertEquals(5, $hasilAvg);
    }

    public function testReduce()
    {
        $koleksi = collect([1,3,5,7,9,11,13,15,17,19]);
        $hasil = $koleksi->reduce(function($carry, $item){
            return $carry + $item;
        });

        self::assertEquals(100, $hasil);
    }
}
