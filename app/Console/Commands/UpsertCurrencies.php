<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;

class UpsertCurrencies extends Command
{
    protected $signature = 'upsert:currencies';

    protected $description = 'Upserts currencies into the database';

    public function handle()
    {
        $currencies = [
            ['code' => 'AFN',  'symbol' => '؋'],
            ['code' => 'ALL',  'symbol' => 'Lek'],
            ['code' => 'ANG',  'symbol' => 'ƒ'],
            ['code' => 'ARS',  'symbol' => '$'],
            ['code' => 'AUD',  'symbol' => '$'],
            ['code' => 'AWG',  'symbol' => 'ƒ'],
            ['code' => 'AZN',  'symbol' => 'ман'],
            ['code' => 'BAM',  'symbol' => 'KM'],
            ['code' => 'BDT',  'symbol' => '৳'],
            ['code' => 'BBD',  'symbol' => '$'],
            ['code' => 'BGN',  'symbol' => 'лв'],
            ['code' => 'BMD',  'symbol' => '$'],
            ['code' => 'BND',  'symbol' => '$'],
            ['code' => 'BOB',  'symbol' => '$b'],
            ['code' => 'BRL',  'symbol' => 'R$'],
            ['code' => 'BSD',  'symbol' => '$'],
            ['code' => 'BWP',  'symbol' => 'P'],
            ['code' => 'BYR',  'symbol' => '₽'],
            ['code' => 'BZD',  'symbol' => 'BZ$'],
            ['code' => 'CAD',  'symbol' => '$'],
            ['code' => 'CHF',  'symbol' => 'CHF'],
            ['code' => 'CLP',  'symbol' => '$'],
            ['code' => 'CNY',  'symbol' => '¥'],
            ['code' => 'COP',  'symbol' => '$'],
            ['code' => 'CRC',  'symbol' => '₡'],
            ['code' => 'CUP',  'symbol' => '₱'],
            ['code' => 'CZK',  'symbol' => 'Kč'],
            ['code' => 'DKK',  'symbol' => 'kr'],
            ['code' => 'DOP',  'symbol' => 'RD$'],
            ['code' => 'EGP',  'symbol' => '£'],
            ['code' => 'EUR',  'symbol' => '€'],
            ['code' => 'FJD',  'symbol' => '$'],
            ['code' => 'FKP',  'symbol' => '£'],
            ['code' => 'GBP',  'symbol' => '£'],
            ['code' => 'GIP',  'symbol' => '£'],
            ['code' => 'GTQ',  'symbol' => 'Q'],
            ['code' => 'GYD',  'symbol' => '$'],
            ['code' => 'HKD',  'symbol' => '$'],
            ['code' => 'HNL',  'symbol' => 'L'],
            ['code' => 'HRK',  'symbol' => 'kn'],
            ['code' => 'HUF',  'symbol' => 'Ft'],
            ['code' => 'IDR',  'symbol' => 'Rp'],
            ['code' => 'ILS',  'symbol' => '₪'],
            ['code' => 'IRR',  'symbol' => '﷼'],
            ['code' => 'ISK',  'symbol' => 'kr'],
            ['code' => 'JMD',  'symbol' => 'J$'],
            ['code' => 'JPY',  'symbol' => '¥'],
            ['code' => 'KGS',  'symbol' => 'лв'],
            ['code' => 'KHR',  'symbol' => '៛'],
            ['code' => 'KPW',  'symbol' => '₩'],
            ['code' => 'KRW',  'symbol' => '₩'],
            ['code' => 'KYD',  'symbol' => '$'],
            ['code' => 'KZT',  'symbol' => 'лв'],
            ['code' => 'LAK',  'symbol' => '₭'],
            ['code' => 'LBP',  'symbol' => '£'],
            ['code' => 'LKR',  'symbol' => '₨'],
            ['code' => 'LRD',  'symbol' => '$'],
            ['code' => 'LTL',  'symbol' => 'Lt'],
            ['code' => 'LVL',  'symbol' => 'Ls'],
            ['code' => 'MKD',  'symbol' => 'ден'],
            ['code' => 'MNT',  'symbol' => '₮'],
            ['code' => 'MUR',  'symbol' => '₨'],
            ['code' => 'MXN',  'symbol' => '$'],
            ['code' => 'MYR',  'symbol' => 'RM'],
            ['code' => 'MZN',  'symbol' => 'MT'],
            ['code' => 'NGN',  'symbol' => '₦'],
            ['code' => 'NIO',  'symbol' => 'C$'],
            ['code' => 'NOK',  'symbol' => 'kr'],
            ['code' => 'NPR',  'symbol' => '₨'],
            ['code' => 'NZD',  'symbol' => '$'],
            ['code' => 'OMR',  'symbol' => '﷼'],
            ['code' => 'PAB',  'symbol' => 'B/.'],
            ['code' => 'PEN',  'symbol' => 'S/.'],
            ['code' => 'PHP',  'symbol' => 'Php'],
            ['code' => 'PKR',  'symbol' => '₨'],
            ['code' => 'PLN',  'symbol' => 'zł'],
            ['code' => 'PYG',  'symbol' => 'Gs'],
            ['code' => 'QAR',  'symbol' => '﷼'],
            ['code' => 'RON',  'symbol' => 'lei'],
            ['code' => 'RSD',  'symbol' => 'Дин.'],
            ['code' => 'RUB',  'symbol' => 'руб'],
            ['code' => 'SAR',  'symbol' => '﷼'],
            ['code' => 'SBD',  'symbol' => '$'],
            ['code' => 'SCR',  'symbol' => '₨'],
            ['code' => 'SEK',  'symbol' => 'kr'],
            ['code' => 'SGD',  'symbol' => '$'],
            ['code' => 'SHP',  'symbol' => '£'],
            ['code' => 'SOS',  'symbol' => 'S'],
            ['code' => 'SRD',  'symbol' => '$'],
            ['code' => 'SVC',  'symbol' => '$'],
            ['code' => 'SYP',  'symbol' => '£'],
            ['code' => 'THB',  'symbol' => '฿'],
            ['code' => 'TRY',  'symbol' => 'TL'],
            ['code' => 'TTD',  'symbol' => 'TT$'],
            ['code' => 'TWD',  'symbol' => 'NT$'],
            ['code' => 'UAH',  'symbol' => '₴'],
            ['code' => 'USD',  'symbol' => '$'],
            ['code' => 'UYU',  'symbol' => '$U'],
            ['code' => 'UZS',  'symbol' => 'лв'],
            ['code' => 'VEF',  'symbol' => 'Bs'],
            ['code' => 'VND',  'symbol' => '₫'],
            ['code' => 'XCD',  'symbol' => '$'],
            ['code' => 'YER',  'symbol' => '﷼'],
            ['code' => 'ZAR',  'symbol' => 'R'],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(
                ['code' => $currency['code']],
                ['symbol' => $currency['symbol']]
            );
        }

        $this->info('Currencies upserted successfully');
    }
}
