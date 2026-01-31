<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Simpan level buffer saat setUp() dipanggil sehingga
     * tearDown() dapat mengembalikan ke keadaan semula.
     */
    protected int $initialObLevel = 0;

    protected function setUp(): void
    {
        parent::setUp();

        // Simpan level buffer saat ini, lalu buka satu buffer untuk test.
        // Ini memungkinkan kita menutup hanya buffer yang dibuka oleh test/app
        // sehingga PHPUnit tidak menganggap ada buffer yang diubah secara global.
        $this->initialObLevel = ob_get_level();
        ob_start();
    }

    protected function tearDown(): void
    {
        // Tutup hanya buffer yang lebih tinggi dari level awal yang disimpan.
        while (ob_get_level() > $this->initialObLevel) {
            @ob_end_clean();
        }

        parent::tearDown();
    }
}
