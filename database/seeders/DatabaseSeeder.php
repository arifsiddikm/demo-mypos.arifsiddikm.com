<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Setting;
use App\Models\Supplier;
use App\Models\Ingredient;
use App\Models\InventoryMovement;
use App\Models\PrinterSetting;
use App\Models\Transaction;
use App\Models\TransactionItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── USERS ──────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Admin MyPOS',
            'email'    => 'admin@mypos.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'is_active'=> true,
        ]);

        $kasir1 = User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'kasir@mypos.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
            'is_active'=> true,
        ]);

        $kasir2 = User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'kasir2@mypos.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
            'is_active'=> true,
        ]);

        $kasir3 = User::create([
            'name'     => 'Ahmad Fauzi',
            'email'    => 'kasir3@mypos.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
            'is_active'=> true,
        ]);

        $kasir4 = User::create([
            'name'     => 'Dewi Lestari',
            'email'    => 'kasir4@mypos.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
            'is_active'=> false,
        ]);

        $allCashiers = [$admin, $kasir1, $kasir2, $kasir3];

        // ─── CATEGORIES ─────────────────────────────────────
        $catAll     = Category::create(['name'=>'Semua',   'slug'=>'all',      'icon'=>'☕', 'sort_order'=>0]);
        $catMinum   = Category::create(['name'=>'Minuman', 'slug'=>'minuman',  'icon'=>'🥤', 'sort_order'=>1]);
        $catMakan   = Category::create(['name'=>'Makanan', 'slug'=>'makanan',  'icon'=>'🍽️', 'sort_order'=>2]);
        $catSnack   = Category::create(['name'=>'Snack',   'slug'=>'snack',    'icon'=>'🍪', 'sort_order'=>3]);
        $catDessert = Category::create(['name'=>'Dessert', 'slug'=>'dessert',  'icon'=>'🍰', 'sort_order'=>4]);
        $catKopi    = Category::create(['name'=>'Kopi',    'slug'=>'kopi',     'icon'=>'☕', 'sort_order'=>5]);
        $catJus     = Category::create(['name'=>'Jus & Smoothie', 'slug'=>'jus', 'icon'=>'🍹', 'sort_order'=>6]);

        // ─── MENUS (55+ items dengan Unsplash images) ───────
        // Format image: URL Unsplash langsung (landscape food photos)
        // unsplash.com/photos/{id}/download?w=400&auto=format&fit=crop
        $menus = [
            // ── KOPI ──
            ['cat'=>$catKopi->id,   'name'=>'Espresso',            'price'=>12000, 'desc'=>'Single shot espresso pekat dan bold',                     'img'=>'https://images.unsplash.com/photo-1510707577719-ae7c14805e3a?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Double Espresso',     'price'=>16000, 'desc'=>'Dua shot espresso untuk yang butuh boost ekstra',          'img'=>'https://images.unsplash.com/photo-1521302200778-33500795e128?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Americano',           'price'=>15000, 'desc'=>'Espresso diencerkan air panas, bersih dan nikmat',          'img'=>'https://images.unsplash.com/photo-1551030173-122aabc4489c?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Cappuccino',          'price'=>22000, 'desc'=>'Espresso, steamed milk, dan foam yang sempurna',            'img'=>'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Latte',               'price'=>23000, 'desc'=>'Espresso lembut dengan susu steam berlimpah',              'img'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Flat White',          'price'=>24000, 'desc'=>'Ristretto dengan microfoam susu yang creamy',              'img'=>'https://images.unsplash.com/photo-1561882468-9110d70d4e2a?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Macchiato',           'price'=>20000, 'desc'=>'Espresso bertopeng foam susu tipis',                       'img'=>'https://images.unsplash.com/photo-1485808191679-5f86510bd9d4?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Kopi Susu',           'price'=>18000, 'desc'=>'Kopi robusta + susu segar ala Indonesia',                  'img'=>'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Kopi Susu Gula Aren', 'price'=>22000, 'desc'=>'Espresso, susu segar, gula aren organik Flores',           'img'=>'https://images.unsplash.com/photo-1562547256-2c5ee93b60b7?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Vietnam Drip',        'price'=>20000, 'desc'=>'Kopi tetes ala Vietnam, slow brew dan kental',             'img'=>'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Cold Brew',           'price'=>25000, 'desc'=>'Kopi cold brew 12 jam, smooth dan low acid',               'img'=>'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Iced Americano',      'price'=>18000, 'desc'=>'Americano dingin segar untuk hari panas',                  'img'=>'https://images.unsplash.com/photo-1517959105821-eaf2591984ca?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Iced Latte',          'price'=>25000, 'desc'=>'Latte dingin dengan es batu kristal',                      'img'=>'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Caramel Macchiato',   'price'=>28000, 'desc'=>'Vanilla latte dengan drizzle caramel di atas',             'img'=>'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catKopi->id,   'name'=>'Mocha',               'price'=>27000, 'desc'=>'Espresso, coklat, steamed milk — harmoni sempurna',        'img'=>'https://images.unsplash.com/photo-1578314675249-a6910f80cc4e?w=400&h=300&fit=crop&auto=format'],

            // ── MINUMAN NON-KOPI ──
            ['cat'=>$catMinum->id,  'name'=>'Matcha Latte',        'price'=>25000, 'desc'=>'Matcha ceremonial grade Uji Jepang dengan steamed milk',   'img'=>'https://images.unsplash.com/photo-1515823662972-da6a2e4d3002?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMinum->id,  'name'=>'Matcha Iced',         'price'=>25000, 'desc'=>'Matcha latte versi dingin yang menyegarkan',               'img'=>'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMinum->id,  'name'=>'Thai Tea',            'price'=>18000, 'desc'=>'Teh Thailand original dengan susu creamer',                'img'=>'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMinum->id,  'name'=>'Taro Latte',          'price'=>24000, 'desc'=>'Talas ungu premium dengan susu steam',                     'img'=>'https://images.unsplash.com/photo-1558618047-f4e8d0b7b5e0?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMinum->id,  'name'=>'Brown Sugar Milk Tea', 'price'=>22000,'desc'=>'Milk tea dengan tiger sugar art yang cantik',              'img'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMinum->id,  'name'=>'Strawberry Lemonade', 'price'=>20000, 'desc'=>'Lemonade segar dengan stroberi asli, manis asam',          'img'=>'https://images.unsplash.com/photo-1523677011781-c91d1bbe2f9e?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMinum->id,  'name'=>'Blue Lemonade',       'price'=>22000, 'desc'=>'Lemonade biru soda dengan butterfly pea tea',              'img'=>'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMinum->id,  'name'=>'Es Teh Manis',        'price'=>8000,  'desc'=>'Teh manis es batu segar, klasik Indonesia',                'img'=>'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMinum->id,  'name'=>'Es Jeruk Peras',      'price'=>12000, 'desc'=>'Jeruk segar diperas langsung, tanpa pengawet',             'img'=>'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMinum->id,  'name'=>'Coklat Panas',        'price'=>20000, 'desc'=>'Dark chocolate Valrhona dengan susu segar',                'img'=>'https://images.unsplash.com/photo-1578314675249-a6910f80cc4e?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMinum->id,  'name'=>'Coklat Iced',         'price'=>22000, 'desc'=>'Hot chocolate versi dingin yang indulgent',                'img'=>'https://images.unsplash.com/photo-1541658016709-a35f6ddf6d19?w=400&h=300&fit=crop&auto=format'],

            // ── JUS & SMOOTHIE ──
            ['cat'=>$catJus->id,    'name'=>'Jus Alpukat',         'price'=>20000, 'desc'=>'Alpukat Hass pilihan, susu, madu, dan es',                 'img'=>'https://images.unsplash.com/photo-1546549032-9571cd6b27df?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catJus->id,    'name'=>'Jus Mangga',          'price'=>18000, 'desc'=>'Mangga harum manis segar tanpa air',                       'img'=>'https://images.unsplash.com/photo-1586771107445-d3ca888129ff?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catJus->id,    'name'=>'Jus Semangka',        'price'=>16000, 'desc'=>'Semangka merah segar, manis alami',                        'img'=>'https://images.unsplash.com/photo-1587049352846-4a222e784d38?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catJus->id,    'name'=>'Jus Tomat',           'price'=>15000, 'desc'=>'Tomat segar kaya vitamin C',                               'img'=>'https://images.unsplash.com/photo-1594735522662-46e1b5cd93be?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catJus->id,    'name'=>'Green Smoothie',      'price'=>28000, 'desc'=>'Bayam, pisang, apel, lemon — detox alami',                 'img'=>'https://images.unsplash.com/photo-1610970881699-44a5587cabec?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catJus->id,    'name'=>'Smoothie Bowl Acai',  'price'=>35000, 'desc'=>'Acai base dengan granola, buah segar, dan madu',           'img'=>'https://images.unsplash.com/photo-1511690656952-34342bb7c2f2?w=400&h=300&fit=crop&auto=format'],

            // ── MAKANAN ──
            ['cat'=>$catMakan->id,  'name'=>'Nasi Goreng Spesial', 'price'=>28000, 'desc'=>'Nasi goreng dengan telur, ayam suwir, dan kerupuk',        'img'=>'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Nasi Goreng Seafood', 'price'=>35000, 'desc'=>'Nasi goreng dengan udang, cumi, dan sayuran',              'img'=>'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Mie Goreng Cafe',     'price'=>25000, 'desc'=>'Mie goreng dengan bakso, telur, sayuran segar',            'img'=>'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Mie Ayam Komplit',    'price'=>28000, 'desc'=>'Mie ayam dengan bakso, ceker, dan pangsit goreng',         'img'=>'https://images.unsplash.com/photo-1617196034183-421b4040ed20?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Pasta Aglio e Olio',  'price'=>35000, 'desc'=>'Spaghetti bawang putih, olive oil, parsley',               'img'=>'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Pasta Carbonara',     'price'=>38000, 'desc'=>'Spaghetti creamy bacon, telur, parmesan',                  'img'=>'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Sandwich Club',       'price'=>30000, 'desc'=>'Triple decker dengan ayam, telur, selada, tomat',          'img'=>'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Burger Crispy Chicken','price'=>32000,'desc'=>'Ayam crispy, selada, tomat, saos spesial, brioche bun',   'img'=>'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Roti Bakar Coklat',   'price'=>15000, 'desc'=>'Roti panggang dengan spread coklat dan keju',              'img'=>'https://images.unsplash.com/photo-1525351326368-efbb5cb6814d?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Pancake Stack',       'price'=>28000, 'desc'=>'3 lapis pancake fluffy dengan maple syrup dan buah',       'img'=>'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Avocado Toast',       'price'=>32000, 'desc'=>'Sourdough panggang, avokad, telur poach, microgreens',     'img'=>'https://images.unsplash.com/photo-1541519227354-08fa5d50c820?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catMakan->id,  'name'=>'Chicken Rice Bowl',   'price'=>30000, 'desc'=>'Nasi putih dengan ayam teriyaki dan salad sesame',         'img'=>'https://images.unsplash.com/photo-1604908177522-ee84a99ab89a?w=400&h=300&fit=crop&auto=format'],

            // ── SNACK ──
            ['cat'=>$catSnack->id,  'name'=>'French Fries Crispy', 'price'=>18000, 'desc'=>'Kentang goreng renyah dengan seasoning special',           'img'=>'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catSnack->id,  'name'=>'Onion Rings',         'price'=>20000, 'desc'=>'Bawang bombay goreng tepung crispy',                       'img'=>'https://images.unsplash.com/photo-1639024471283-03518883512d?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catSnack->id,  'name'=>'Croissant Butter',    'price'=>18000, 'desc'=>'Croissant all-butter asli, flaky dan buttery',             'img'=>'https://images.unsplash.com/photo-1555507036-ab1f4038808a?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catSnack->id,  'name'=>'Croissant Almond',    'price'=>22000, 'desc'=>'Croissant dengan isian krim almond dan topping flaked almond','img'=>'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catSnack->id,  'name'=>'Pisang Goreng Keju',  'price'=>15000, 'desc'=>'Pisang goreng crispy dengan taburan keju cheddar',         'img'=>'https://images.unsplash.com/photo-1630426767020-53a5848e530c?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catSnack->id,  'name'=>'Tahu Crispy',         'price'=>12000, 'desc'=>'Tahu goreng crispy dengan sambal kacang',                  'img'=>'https://images.unsplash.com/photo-1626804475297-41608ea09aeb?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catSnack->id,  'name'=>'Chicken Wings',       'price'=>32000, 'desc'=>'6 pcs chicken wings dengan saus pilihan (BBQ/Buffalo)',    'img'=>'https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catSnack->id,  'name'=>'Bruschetta',          'price'=>22000, 'desc'=>'Roti sourdough panggang dengan tomat, basil, olive oil',   'img'=>'https://images.unsplash.com/photo-1572695157366-5e585ab2b69f?w=400&h=300&fit=crop&auto=format'],

            // ── DESSERT ──
            ['cat'=>$catDessert->id,'name'=>'Cheesecake Slice',    'price'=>28000, 'desc'=>'New York style cheesecake dengan saus berry',              'img'=>'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catDessert->id,'name'=>'Chocolate Lava Cake', 'price'=>30000, 'desc'=>'Kue coklat hangat dengan lelehan coklat di dalam',         'img'=>'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catDessert->id,'name'=>'Tiramisu',            'price'=>32000, 'desc'=>'Tiramisu clasico dengan kopi dan mascarpone',              'img'=>'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catDessert->id,'name'=>'Es Krim Vanilla',     'price'=>18000, 'desc'=>'2 scoop es krim vanilla madagascar premium',              'img'=>'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catDessert->id,'name'=>'Sundae Coklat',       'price'=>22000, 'desc'=>'Vanilla ice cream dengan hot fudge dan whipped cream',     'img'=>'https://images.unsplash.com/photo-1576506295286-5cda18df43e7?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catDessert->id,'name'=>'Puding Coklat',       'price'=>12000, 'desc'=>'Puding coklat silky smooth dengan saus vanila',            'img'=>'https://images.unsplash.com/photo-1541783245831-57d6fb0926d3?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catDessert->id,'name'=>'Waffle & Ice Cream',  'price'=>35000, 'desc'=>'Waffle crispy hangat dengan 2 scoop ice cream',            'img'=>'https://images.unsplash.com/photo-1568051243858-533a607809a5?w=400&h=300&fit=crop&auto=format'],
            ['cat'=>$catDessert->id,'name'=>'Crepes Strawberry',   'price'=>25000, 'desc'=>'Crepes tipis dengan stroberi segar dan krim',              'img'=>'https://images.unsplash.com/photo-1519676867240-f03562e64548?w=400&h=300&fit=crop&auto=format'],
        ];

        $createdMenus = [];
        foreach ($menus as $i => $menu) {
            $createdMenus[] = Menu::create([
                'category_id'  => $menu['cat'],
                'name'         => $menu['name'],
                'description'  => $menu['desc'],
                'price'        => $menu['price'],
                'image'        => $menu['img'],
                'is_available' => true,
                'sort_order'   => $i,
            ]);
        }

        // ─── TABLES ─────────────────────────────────────────
        $tables = [
            ['name'=>'Meja 1',  'capacity'=>2, 'pos_x'=>50,  'pos_y'=>50],
            ['name'=>'Meja 2',  'capacity'=>2, 'pos_x'=>200, 'pos_y'=>50],
            ['name'=>'Meja 3',  'capacity'=>4, 'pos_x'=>400, 'pos_y'=>80],
            ['name'=>'Meja 4',  'capacity'=>4, 'pos_x'=>600, 'pos_y'=>50],
            ['name'=>'Meja 5',  'capacity'=>6, 'pos_x'=>50,  'pos_y'=>220],
            ['name'=>'Meja 6',  'capacity'=>4, 'pos_x'=>280, 'pos_y'=>230],
            ['name'=>'Meja 7',  'capacity'=>2, 'pos_x'=>500, 'pos_y'=>210],
            ['name'=>'Meja 8',  'capacity'=>4, 'pos_x'=>650, 'pos_y'=>240],
            ['name'=>'Meja 9',  'capacity'=>8, 'pos_x'=>100, 'pos_y'=>380],
            ['name'=>'Meja 10', 'capacity'=>4, 'pos_x'=>400, 'pos_y'=>370],
            ['name'=>'Bar 1',   'capacity'=>2, 'pos_x'=>750, 'pos_y'=>100],
            ['name'=>'Bar 2',   'capacity'=>2, 'pos_x'=>750, 'pos_y'=>200],
            ['name'=>'VIP 1',   'capacity'=>6, 'pos_x'=>600, 'pos_y'=>370],
            ['name'=>'VIP 2',   'capacity'=>8, 'pos_x'=>600, 'pos_y'=>480],
            ['name'=>'Outdoor', 'capacity'=>4, 'pos_x'=>200, 'pos_y'=>450],
        ];

        $createdTables = [];
        foreach ($tables as $t) {
            $createdTables[] = Table::create($t);
        }

        // ─── SUPPLIERS ──────────────────────────────────────
        $sup1 = Supplier::create(['name'=>'PT. Kopi Nusantara', 'contact_person'=>'Budi Santoso', 'phone'=>'081234567890', 'email'=>'budi@kopinusantara.com', 'address'=>'Jl. Raya Kopi No. 1, Jakarta Selatan']);
        $sup2 = Supplier::create(['name'=>'UD. Sari Rasa', 'contact_person'=>'Dewi Mulyani', 'phone'=>'082345678901', 'email'=>'dewi@sarirasa.co.id', 'address'=>'Jl. Pasar Minggu No. 12, Jakarta']);
        $sup3 = Supplier::create(['name'=>'CV. Dapur Sehat', 'contact_person'=>'Ahmad Basuki', 'phone'=>'083456789012', 'email'=>'ahmad@dapursehat.com', 'address'=>'Jl. Kemang Raya No. 5, Bogor']);
        $sup4 = Supplier::create(['name'=>'PT. Fresh Farm Indonesia', 'contact_person'=>'Rina Kartika', 'phone'=>'084567890123', 'email'=>'rina@freshfarm.id', 'address'=>'Jl. Cipanas No. 88, Cianjur']);
        $sup5 = Supplier::create(['name'=>'Toko Bahan Kue Bu Tini', 'contact_person'=>'Tini Wahyuni', 'phone'=>'085678901234', 'email'=>'tini@tokobuatinkue.com', 'address'=>'Jl. Kemiri No. 7, Tangerang']);

        // ─── INGREDIENTS ────────────────────────────────────
        $ingredients = [
            ['supplier_id'=>$sup1->id, 'name'=>'Biji Kopi Arabika Gayo',  'unit'=>'kg',    'stock'=>15,   'min_stock'=>3,   'cost_per_unit'=>180000],
            ['supplier_id'=>$sup1->id, 'name'=>'Biji Kopi Robusta',        'unit'=>'kg',    'stock'=>10,   'min_stock'=>2,   'cost_per_unit'=>90000],
            ['supplier_id'=>$sup2->id, 'name'=>'Susu Full Cream',           'unit'=>'liter', 'stock'=>30,   'min_stock'=>5,   'cost_per_unit'=>18000],
            ['supplier_id'=>$sup2->id, 'name'=>'Susu Oat',                  'unit'=>'liter', 'stock'=>10,   'min_stock'=>2,   'cost_per_unit'=>35000],
            ['supplier_id'=>$sup2->id, 'name'=>'Susu Almond',               'unit'=>'liter', 'stock'=>8,    'min_stock'=>2,   'cost_per_unit'=>45000],
            ['supplier_id'=>$sup2->id, 'name'=>'Gula Pasir',                'unit'=>'kg',    'stock'=>20,   'min_stock'=>5,   'cost_per_unit'=>14000],
            ['supplier_id'=>$sup2->id, 'name'=>'Gula Aren Cair',            'unit'=>'liter', 'stock'=>5,    'min_stock'=>1,   'cost_per_unit'=>55000],
            ['supplier_id'=>$sup1->id, 'name'=>'Matcha Powder Ceremonial',  'unit'=>'kg',    'stock'=>2,    'min_stock'=>0.5, 'cost_per_unit'=>280000],
            ['supplier_id'=>$sup1->id, 'name'=>'Taro Powder',               'unit'=>'kg',    'stock'=>3,    'min_stock'=>0.5, 'cost_per_unit'=>120000],
            ['supplier_id'=>$sup1->id, 'name'=>'Coklat Bubuk Premium',      'unit'=>'kg',    'stock'=>5,    'min_stock'=>1,   'cost_per_unit'=>95000],
            ['supplier_id'=>$sup2->id, 'name'=>'Sirup Vanilla',             'unit'=>'liter', 'stock'=>3,    'min_stock'=>0.5, 'cost_per_unit'=>65000],
            ['supplier_id'=>$sup2->id, 'name'=>'Sirup Caramel',             'unit'=>'liter', 'stock'=>3,    'min_stock'=>0.5, 'cost_per_unit'=>65000],
            ['supplier_id'=>$sup4->id, 'name'=>'Telur Ayam',                'unit'=>'butir', 'stock'=>120,  'min_stock'=>24,  'cost_per_unit'=>2000],
            ['supplier_id'=>$sup3->id, 'name'=>'Tepung Terigu Protein T.',  'unit'=>'kg',    'stock'=>15,   'min_stock'=>3,   'cost_per_unit'=>12000],
            ['supplier_id'=>$sup2->id, 'name'=>'Mentega Unsalted',          'unit'=>'kg',    'stock'=>5,    'min_stock'=>1,   'cost_per_unit'=>80000],
            ['supplier_id'=>$sup4->id, 'name'=>'Avokad Hass',               'unit'=>'kg',    'stock'=>8,    'min_stock'=>2,   'cost_per_unit'=>28000],
            ['supplier_id'=>$sup4->id, 'name'=>'Stroberi Segar',            'unit'=>'kg',    'stock'=>5,    'min_stock'=>1,   'cost_per_unit'=>45000],
            ['supplier_id'=>$sup4->id, 'name'=>'Pisang Cavendish',          'unit'=>'kg',    'stock'=>10,   'min_stock'=>2,   'cost_per_unit'=>10000],
            ['supplier_id'=>$sup3->id, 'name'=>'Dada Ayam Fillet',          'unit'=>'kg',    'stock'=>12,   'min_stock'=>3,   'cost_per_unit'=>45000],
            ['supplier_id'=>$sup3->id, 'name'=>'Nasi Putih (beras)',        'unit'=>'kg',    'stock'=>25,   'min_stock'=>5,   'cost_per_unit'=>13000],
            ['supplier_id'=>$sup3->id, 'name'=>'Mie Telur',                 'unit'=>'kg',    'stock'=>8,    'min_stock'=>2,   'cost_per_unit'=>18000],
            ['supplier_id'=>$sup5->id, 'name'=>'Dark Chocolate Valrhona',   'unit'=>'kg',    'stock'=>3,    'min_stock'=>0.5, 'cost_per_unit'=>220000],
            ['supplier_id'=>$sup5->id, 'name'=>'Cream Cheese Philadelphia', 'unit'=>'kg',    'stock'=>4,    'min_stock'=>1,   'cost_per_unit'=>180000],
            ['supplier_id'=>$sup5->id, 'name'=>'Heavy Whipping Cream',      'unit'=>'liter', 'stock'=>6,    'min_stock'=>1,   'cost_per_unit'=>65000],
            ['supplier_id'=>$sup2->id, 'name'=>'Air Mineral',               'unit'=>'liter', 'stock'=>50,   'min_stock'=>10,  'cost_per_unit'=>3000],
        ];

        $createdIngredients = [];
        foreach ($ingredients as $ing) {
            $createdIngredients[] = Ingredient::create($ing);
        }

        // ─── INVENTORY MOVEMENTS ────────────────────────────
        // Simulasikan pembelian stok 2 bulan terakhir
        $movementData = [
            ['ingredient_id'=>1, 'supplier_id'=>$sup1->id, 'user_id'=>$admin->id, 'type'=>'in',  'quantity'=>20, 'cost_per_unit'=>180000, 'notes'=>'Pembelian bulanan', 'days_ago'=>45],
            ['ingredient_id'=>2, 'supplier_id'=>$sup1->id, 'user_id'=>$admin->id, 'type'=>'in',  'quantity'=>15, 'cost_per_unit'=>90000,  'notes'=>'Restok robusta',    'days_ago'=>40],
            ['ingredient_id'=>3, 'supplier_id'=>$sup2->id, 'user_id'=>$admin->id, 'type'=>'in',  'quantity'=>40, 'cost_per_unit'=>18000,  'notes'=>'Susu mingguan',     'days_ago'=>30],
            ['ingredient_id'=>6, 'supplier_id'=>$sup2->id, 'user_id'=>$admin->id, 'type'=>'in',  'quantity'=>30, 'cost_per_unit'=>14000,  'notes'=>'Gula bulanan',      'days_ago'=>35],
            ['ingredient_id'=>8, 'supplier_id'=>$sup1->id, 'user_id'=>$admin->id, 'type'=>'in',  'quantity'=>3,  'cost_per_unit'=>280000, 'notes'=>'Matcha premium',    'days_ago'=>25],
            ['ingredient_id'=>13,'supplier_id'=>$sup4->id, 'user_id'=>$admin->id, 'type'=>'in',  'quantity'=>200,'cost_per_unit'=>2000,   'notes'=>'Telur mingguan',    'days_ago'=>7],
            ['ingredient_id'=>19,'supplier_id'=>$sup3->id, 'user_id'=>$admin->id, 'type'=>'in',  'quantity'=>15, 'cost_per_unit'=>45000,  'notes'=>'Ayam segar',        'days_ago'=>5],
            ['ingredient_id'=>20,'supplier_id'=>$sup3->id, 'user_id'=>$admin->id, 'type'=>'in',  'quantity'=>30, 'cost_per_unit'=>13000,  'notes'=>'Beras premium',     'days_ago'=>14],
            ['ingredient_id'=>1, 'supplier_id'=>null,      'user_id'=>$kasir1->id,'type'=>'out', 'quantity'=>5,  'cost_per_unit'=>null,   'notes'=>'Pemakaian espresso','days_ago'=>3],
            ['ingredient_id'=>3, 'supplier_id'=>null,      'user_id'=>$kasir2->id,'type'=>'out', 'quantity'=>10, 'cost_per_unit'=>null,   'notes'=>'Pemakaian minuman', 'days_ago'=>2],
            ['ingredient_id'=>22,'supplier_id'=>$sup5->id, 'user_id'=>$admin->id, 'type'=>'in',  'quantity'=>4,  'cost_per_unit'=>220000, 'notes'=>'Dark choco dessert','days_ago'=>10],
            ['ingredient_id'=>23,'supplier_id'=>$sup5->id, 'user_id'=>$admin->id, 'type'=>'in',  'quantity'=>5,  'cost_per_unit'=>180000, 'notes'=>'Cream cheese cake', 'days_ago'=>10],
        ];

        foreach ($movementData as $mv) {
            InventoryMovement::create([
                'ingredient_id' => $mv['ingredient_id'],
                'supplier_id'   => $mv['supplier_id'],
                'user_id'       => $mv['user_id'],
                'type'          => $mv['type'],
                'quantity'      => $mv['quantity'],
                'cost_per_unit' => $mv['cost_per_unit'],
                'notes'         => $mv['notes'],
                'movement_date' => Carbon::now()->subDays($mv['days_ago']),
            ]);
        }

        // ─── TRANSACTIONS (80+ dummy) ────────────────────────
        $paymentMethods = ['cash', 'cash', 'cash', 'transfer', 'qris', 'qris'];
        $orderTypes     = ['dine_in', 'dine_in', 'dine_in', 'takeaway'];

        // Pilih menu yang bagus untuk transaksi (exclude kategori 'Semua')
        $menuPool = collect($createdMenus)->filter(fn($m) => $m->category_id > 1)->values();

        $invoiceCounter = 1;

        for ($dayAgo = 60; $dayAgo >= 0; $dayAgo--) {
            // 1-5 transaksi per hari, lebih banyak di hari-hari belakangan
            $txPerDay = $dayAgo < 7 ? rand(4, 7) : rand(1, 4);

            for ($t = 0; $t < $txPerDay; $t++) {
                $date         = Carbon::now()->subDays($dayAgo)->setTime(rand(8,21), rand(0,59));
                $cashier      = $allCashiers[array_rand($allCashiers)];
                $table        = rand(0,1) ? $createdTables[array_rand($createdTables)] : null;
                $orderType    = $table ? 'dine_in' : 'takeaway';
                $payMethod    = $paymentMethods[array_rand($paymentMethods)];
                $isCancelled  = rand(1,10) === 1; // 10% dibatalkan

                $invoiceNo = 'INV-' . $date->format('Ymd') . '-' . str_pad($invoiceCounter++, 4, '0', STR_PAD_LEFT);

                // Pilih 1-5 item acak
                $itemCount   = rand(1, 5);
                $selectedMenus = $menuPool->random(min($itemCount, $menuPool->count()));
                $subtotal    = 0;

                $txStatus  = $isCancelled ? 'cancelled' : 'paid';
                $paidAmt   = 0;
                $changeAmt = 0;

                // Hitung subtotal dulu
                $itemsData = [];
                foreach ($selectedMenus as $menu) {
                    $qty = rand(1, 3);
                    $sub = $menu->price * $qty;
                    $subtotal += $sub;
                    $itemsData[] = ['menu' => $menu, 'qty' => $qty, 'sub' => $sub];
                }

                $tax      = 0;
                $discount = 0;
                $total    = $subtotal + $tax - $discount;

                if ($txStatus === 'paid') {
                    // Bulatkan ke atas ke ribuan terdekat
                    $paidAmt  = ceil($total / 1000) * 1000;
                    if ($payMethod === 'cash') {
                        $paidAmt = $paidAmt + (rand(0,2) * 5000); // kadang bayar lebih
                    } else {
                        $paidAmt = $total; // transfer/qris pas
                    }
                    $changeAmt = $paidAmt - $total;
                }

                $tx = Transaction::create([
                    'invoice_number' => $invoiceNo,
                    'user_id'        => $cashier->id,
                    'table_id'       => $table?->id,
                    'order_type'     => $orderType,
                    'status'         => $txStatus,
                    'payment_method' => $txStatus === 'paid' ? $payMethod : null,
                    'subtotal'       => $subtotal,
                    'tax'            => $tax,
                    'discount'       => $discount,
                    'total'          => $total,
                    'paid_amount'    => $paidAmt,
                    'change_amount'  => $changeAmt,
                    'notes'          => null,
                    'paid_at'        => $txStatus === 'paid' ? $date : null,
                    'created_at'     => $date,
                    'updated_at'     => $date,
                ]);

                foreach ($itemsData as $item) {
                    TransactionItem::create([
                        'transaction_id' => $tx->id,
                        'menu_id'        => $item['menu']->id,
                        'menu_name'      => $item['menu']->name,
                        'price'          => $item['menu']->price,
                        'quantity'       => $item['qty'],
                        'subtotal'       => $item['sub'],
                        'notes'          => null,
                        'created_at'     => $date,
                        'updated_at'     => $date,
                    ]);
                }
            }
        }

        // ─── PRINTER SETTINGS ───────────────────────────────
        PrinterSetting::create([
            'printer_name'  => null,
            'printer_type'  => 'thermal',
            'auto_print'    => false,
            'paper_size'    => '58mm',
            'header_text'   => "MyPOS Cafe\nJl. Kopi Enak No. 1, Jakarta\nTelp: 081234567890",
            'footer_text'   => "Terima kasih telah berkunjung!\nSampai jumpa lagi ☕",
        ]);

        // ─── SETTINGS ───────────────────────────────────────
        $settings = [
            'cafe_name'        => 'MyPOS Cafe',
            'cafe_address'     => 'Jl. Kopi Enak No. 1, Jakarta Selatan',
            'cafe_phone'       => '081234567890',
            'cafe_email'       => 'hello@mypos.cafe',
            'tax_percentage'   => '0',
            'cafe_description' => 'Cafe modern dengan nuansa hangat, menyajikan kopi dan makanan berkualitas.',
            'cafe_tagline'     => 'Every Cup Tells a Story',
        ];

        foreach ($settings as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }

        $this->command->info('✅ Seeder selesai!');
        $this->command->info('👑 Admin: admin@mypos.com / password');
        $this->command->info('🧑‍💼 Kasir: kasir@mypos.com / password');
        $this->command->info('📦 ' . count($createdMenus) . ' menu, ' . count($createdIngredients) . ' bahan, 5 supplier, 15 meja');
        $this->command->info('💳 ' . Transaction::count() . ' transaksi dummy dibuat');
    }
}
