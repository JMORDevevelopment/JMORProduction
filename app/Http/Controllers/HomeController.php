<?php

namespace App\Http\Controllers;

use App\Models\HomeTab;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class HomeController extends Controller
{
    // Index / Home page (using your Eloquent models)
    public function index()
    {
        $title = Setting::get('meta_title', '');
        $description = Setting::get('meta_description', '');
        $keywords = Setting::get('meta_keyword', '');

        $mainServices = Service::all()->toArray();
        $homeTabs = HomeTab::orderBy('tab_id', 'asc')->get()->toArray();
        $mainSliders = Slider::orderBy('priority', 'asc')->get()->toArray();

        return view('frontend.home', compact(
            'title',
            'description',
            'keywords',
            'mainServices',
            'homeTabs',
            'mainSliders'
        ));
    }

    // ---- Blog ----
    public function blog_posts()
    {
        $data['title'] = 'Blog';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.blog', $data);
    }

    public function blog_detail($blog_link)
    {
        $blog = DB::table('blog')->where('link', $blog_link)->first();
        if (! $blog) {
            abort(404);
        }
        $data['blog_datas'] = (array) $blog;
        $data['title'] = $blog->meta_title ?: $blog->name;
        $data['description'] = strip_tags($blog->meta_description ?? '');
        $data['keywords'] = $blog->meta_keywords ?? '';

        return view('frontend.blog_detail', $data);
    }

    // ---- Testimonials ----
    public function testimonials()
    {
        $data['title'] = 'Testimonials';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.testimonials', $data);
    }

    // ---- Search ----
    public function search_result(Request $request)
    {
        $search_key = $request->input('search');
        $data['title'] = 'Search';
        $data['description'] = '';
        $data['keywords'] = '';
        $data['main_blogs'] = [];
        if (! empty($search_key)) {
            $blogs = DB::table('blog')->where('name', 'like', '%'.$search_key.'%')->limit(10)->get()->toArray();
            $pages = DB::table('pages')->where('name', 'like', '%'.$search_key.'%')->limit(10)->get()->toArray();
            $data['main_blogs'] = array_merge($blogs, $pages);
        }

        return view('frontend.search', $data);
    }

    public function search_radio(Request $request)
    {
        $search_key = $request->input('search');
        $date_key = $request->input('show_date');
        $data['title'] = 'Search';
        $data['description'] = '';
        $data['keywords'] = '';
        $query = DB::table('radio_show');
        if (! empty($date_key)) {
            $show_date = date('Y-m-d', strtotime($date_key));
            $query->where('show_date', $show_date);
        }
        if (! empty($search_key)) {
            $query->where('name', 'like', '%'.$search_key.'%');
        }
        $data['main_blogs'] = $query->get()->toArray();

        return view('frontend.search_radio', $data);
    }

    // ---- Case Studies ----
    public function case_studies_posts()
    {
        $data['title'] = 'Case Studies';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.case_studies', $data);
    }

    public function case_studies_detail($link)
    {
        $case = DB::table('case_studies')->where('link', $link)->first();
        if (! $case) {
            abort(404);
        }
        $data['case_studies_datas'] = (array) $case;
        $data['title'] = $case->meta_title ?: $case->name;
        $data['description'] = strip_tags($case->meta_description ?? '');
        $data['keywords'] = $case->meta_keywords ?? '';

        return view('frontend.case_studies_detail', $data);
    }

    // ---- Jmor Shows (Radio) ----
    public function jmor_radio_posts()
    {
        $data['title'] = 'Jmor Shows';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.jmor_radio', $data);
    }

    public function jmor_radio_detail($link)
    {
        $show = DB::table('radio_show')->where('link', $link)->first();
        if (! $show) {
            abort(404);
        }
        $data['jmor_radio_datas'] = (array) $show;
        $data['title'] = $show->meta_title ?: $show->name;
        $data['description'] = strip_tags($show->meta_description ?? '');
        $data['keywords'] = $show->meta_keywords ?? '';

        return view('frontend.jmor_radio_detail', $data);
    }

    public function category_radio_show($cat_link, $cYear = null)
    {
        $category = DB::table('category_radio_show')->where('link', $cat_link)->first();
        if (! $category) {
            abort(404);
        }
        $data['category_radio_show'] = [(array) $category];
        $data['top_title'] = $category->title;
        $data['category_id'] = $category->id;
        $data['currentYear'] = $cYear;
        $children = DB::table('category_radio_show')->where('parent_id', $category->id)->get()->toArray();
        $data['category_radio_show_child'] = $children;
        $data['title'] = $category->meta_title ?: $category->title;
        $data['description'] = $category->meta_keywords ?? '';
        $data['keywords'] = $category->meta_description ?? '';

        $shows = [];
        if ($category->parent_id == 0) {
            $record1 = DB::table('radio_show')->where('category_id', $category->id)->orderBy('id', 'desc')->get()->toArray();
            $record2 = [];
            if (! empty($children)) {
                $childIds = array_column($children, 'id');
                $record2 = DB::table('radio_show')->whereIn('category_id', $childIds)->orderBy('id', 'desc')->get()->toArray();
            }
            $shows = array_merge($record1, $record2);
        } else {
            $shows = DB::table('radio_show')->where('category_id', $category->id)->orderBy('id', 'desc')->get()->toArray();
        }
        $data['show_datas'] = $shows;

        return view('frontend.category_radio_show', $data);
    }

    // ---- Recommended ----
    public function recommended_posts()
    {
        $data['title'] = 'Recommended';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.recommended', $data);
    }

    public function recommended_detail($link)
    {
        $rec = DB::table('recommended')->where('link', $link)->first();
        if (! $rec) {
            abort(404);
        }
        $data['recommended_datas'] = (array) $rec;
        $data['title'] = $rec->meta_title ?: $rec->name;
        $data['description'] = strip_tags($rec->meta_description ?? '');
        $data['keywords'] = $rec->meta_keywords ?? '';

        return view('frontend.recommended_detail', $data);
    }

    // ---- Random Acts of Kindness ----
    public function random_acts_of_kindness_posts()
    {
        $data['title'] = 'Random Acts of Kindness';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.random_acts_of_kindness', $data);
    }

    public function random_acts_of_kindness_detail($link)
    {
        $row = DB::table('random_acts_of_kindness')->where('link', $link)->first();
        if (! $row) {
            abort(404);
        }
        $data['random_acts_of_kindness_datas'] = (array) $row;
        $data['title'] = $row->meta_title ?: $row->name;
        $data['description'] = strip_tags($row->meta_description ?? '');
        $data['keywords'] = $row->meta_keywords ?? '';

        return view('frontend.random_acts_of_kindness_detail', $data);
    }

    // ---- Events ----
    public function events_posts()
    {
        $data['title'] = 'Events';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.events', $data);
    }

    public function events_detail($link)
    {
        $row = DB::table('events')->where('link', $link)->first();
        if (! $row) {
            abort(404);
        }
        $data['events_datas'] = (array) $row;
        $data['title'] = $row->meta_title ?: $row->name;
        $data['description'] = strip_tags($row->meta_description ?? '');
        $data['keywords'] = $row->meta_keywords ?? '';

        return view('frontend.events_detail', $data);
    }

    // ---- Media Resources ----
    public function media_resources_posts()
    {
        $data['title'] = 'Media Resources';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.media_resources', $data);
    }

    public function media_resources_detail($link)
    {
        $row = DB::table('media_resouces')->where('link', $link)->first();
        if (! $row) {
            abort(404);
        }
        $data['media_resouces_datas'] = (array) $row;
        $data['title'] = $row->meta_title ?: $row->name;
        $data['description'] = strip_tags($row->meta_description ?? '');
        $data['keywords'] = $row->meta_keywords ?? '';

        return view('frontend.media_resources_detail', $data);
    }

    // ---- Press Releases ----
    public function press_releases_posts()
    {
        $data['title'] = 'Press Releases';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.press_releases', $data);
    }

    public function press_releases_detail($link)
    {
        $row = DB::table('press_releases')->where('link', $link)->first();
        if (! $row) {
            abort(404);
        }
        $data['press_releases_datas'] = (array) $row;
        $data['title'] = $row->meta_title ?: $row->name;
        $data['description'] = strip_tags($row->meta_description ?? '');
        $data['keywords'] = $row->meta_keywords ?? '';

        return view('frontend.press_releases_detail', $data);
    }

    // ---- Media Video ----
    public function media_video_posts()
    {
        $data['title'] = 'Media Video';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.media_video', $data);
    }

    public function media_video_detail($link)
    {
        $row = DB::table('media_video')->where('link', $link)->first();
        if (! $row) {
            abort(404);
        }
        $data['media_video_datas'] = (array) $row;
        $data['title'] = $row->meta_title ?: $row->name;
        $data['description'] = strip_tags($row->meta_description ?? '');
        $data['keywords'] = $row->meta_keywords ?? '';

        return view('frontend.media_video_detail', $data);
    }

    // ---- Brand Guidelines ----
    public function brand_guidelines_posts()
    {
        $data['title'] = 'Brand Guidelines';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.brand_guidelines', $data);
    }

    public function brand_guidelines_detail($link)
    {
        $row = DB::table('brand_guidelines')->where('link', $link)->first();
        if (! $row) {
            abort(404);
        }
        $data['brand_guidelines_datas'] = (array) $row;
        $data['title'] = $row->meta_title ?: $row->name;
        $data['description'] = strip_tags($row->meta_description ?? '');
        $data['keywords'] = $row->meta_keywords ?? '';

        return view('frontend.brand_guidelines_detail', $data);
    }

    // ---- Services ----
    public function service_list()
    {
        $data['title'] = 'Services';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.service', $data);
    }

    public function service_detail($link)
    {
        $row = DB::table('service')->where('link', $link)->first();
        if (! $row) {
            abort(404);
        }
        $data['service_datas'] = (array) $row;
        $data['title'] = $row->meta_title ?: $row->name;
        $data['description'] = strip_tags($row->meta_description ?? '');
        $data['keywords'] = $row->meta_keywords ?? '';

        return view('frontend.service_detail', $data);
    }

    // ---- News ----
    public function news_list($start = 0)
    {
        $limit = 5;
        $total = DB::table('news')->count();
        $news = DB::table('news')->offset($start)->limit($limit)->get()->toArray();
        $data['news'] = array_map(function ($n) {
            return (array) $n;
        }, $news);
        $to = min($start + $limit, $total);
        $data['text_showing'] = ($total > 0) ? sprintf('Showing %d to %d of %d', $start + 1, $to, $total) : 'No results';
        $data['pagination'] = ''; // You can add Laravel pagination if needed
        $data['title'] = 'News';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.news', $data);
    }

    public function news_detail($link)
    {
        $row = DB::table('news')->where('link', $link)->first();
        if (! $row) {
            abort(404);
        }
        $data['news_datas'] = (array) $row;
        $data['title'] = $row->meta_title ?: $row->name;
        $data['description'] = strip_tags($row->meta_description ?? '');
        $data['keywords'] = $row->meta_keywords ?? '';

        return view('frontend.news_detail', $data);
    }

    // ---- Pages (catch-all) ----
    public function pages($page_link)
    {
        $page = DB::table('pages')->where('link', $page_link)->first();
        if (! $page) {
            abort(404);
        }
        $data['page_datas'] = (array) $page;
        $data['title'] = $page->meta_title ?: $page->name;
        $data['description'] = strip_tags($page->meta_description ?? '');
        $data['keywords'] = $page->meta_keywords ?? '';

        return view('frontend.pages', $data);
    }

    // ---- Packages ----
    public function packages_list()
    {
        $data['package_data'] = DB::table('packages')->orderBy('priority', 'asc')->get()->toArray();
        $data['title'] = 'Packages';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.packages', $data);
    }

    public function packages_detail($category_name)
    {
        $data['top_title'] = $category_name;
        $data['package_data'] = DB::table('packages')->where('category_name', $category_name)->orderBy('priority', 'asc')->get()->toArray();
        $data['title'] = 'Jmor Services';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.packages_detail', $data);
    }

    public function single_package($package_id)
    {
        $packages = DB::table('packages')
            ->where('id', $package_id)
            ->orderBy('priority', 'asc')
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();

        $data['package_data'] = $packages;
        $data['title'] = 'Jmor Services';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.single_package', $data);
    }

    // ---- Gift Card ----
    public function gift_card_list()
    {
        $data['title'] = 'Gift Card';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.gift_card', $data);
    }

    // ---- Contact ----
    public function contact_us(Request $request)
    {
        $data['title'] = 'Contact Us';
        $data['description'] = '';
        $data['keywords'] = '';
        $min = 1;
        $max = 15;
        $data['random_number1'] = mt_rand($min, $max);
        $data['random_number2'] = mt_rand($min, $max);
        $data['random_number1_show'] = $data['random_number1'];
        $data['random_number2_show'] = $data['random_number2'];
        $data['name'] = old('name', '');
        $data['email'] = old('email', '');
        $data['phone'] = old('phone', '');
        $data['reason'] = old('reason', '');
        $data['message'] = old('message', '');
        $data['error'] = session('errors') ? session('errors')->getBag('default')->getMessages() : [];

        return view('frontend.contact_us', $data);
    }

    public function contactusValidate(Request $request)
    {
        $post = $request->all();
        $error = [];

        if (empty($post['name'])) {
            $error['error_name'] = 'Enter Name';
        }
        if (empty($post['email'])) {
            $error['error_email'] = 'Enter Email';
        }
        if (empty($post['phone'])) {
            $error['error_phone'] = 'Enter Phone';
        }
        if (empty($post['reason'])) {
            $error['error_reason'] = 'Enter Reason';
        }
        if (empty($post['message'])) {
            $error['error_message'] = 'Enter Message';
        }

        $rand1 = $request->input('firstNumber');
        $rand2 = $request->input('secondNumber');
        $total = $request->input('protection_question');
        if ($rand1 + $rand2 != $total) {
            $error['error_protection_question'] = 'Your answer is wrong!';
        }

        if (! empty($error)) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        // Insert contact_us
        $insert = [
            'name' => htmlspecialchars($post['name']),
            'email' => htmlspecialchars($post['email']),
            'phone' => htmlspecialchars($post['phone']),
            'reason' => $post['reason'],
            'message' => $post['message'],
            'ip' => $request->ip(),
            'date_time' => date('m/d/y h:i:s a'),
        ];
        DB::table('contact_us')->insert($insert);

        // Send email (replicate extension_model->email_queue)
        $to = DB::table('settings')->where('option', 'email')->value('value');
        $subject = 'Contact Us';
        $from = $post['email'];
        $message = "<p style='color:black;'><strong>Name: </strong>".$post['name'].'</p>';
        $message .= "<br><strong style='color:black;'>Email: </strong>".$post['email'];
        $message .= "<br><strong style='color:black;'>Phone: </strong>".$post['phone'];
        $message .= "<br><strong style='color:black;'>Reason: </strong>".$post['reason'];
        $message .= "<br><strong style='color:black;'>Message: </strong>".$post['message'];

        try {
            Mail::html($message, function ($mail) use ($to, $subject, $from) {
                $mail->to($to)->subject($subject)->from($from);
            });
        } catch (\Exception $e) {
            // ignore
        }

        return redirect('/contact?form=submit');
    }

    // ---- Checkout / Cart ----
    public function check_out()
    {
        if (! session()->has('user_id')) {
            return redirect('/login');
        }
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect('/');
        }
        $data['cartItems'] = $cartItems;
        $data['title'] = 'Check Out';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.checkout', $data);
    }

    public function checkout_confirm()
    {
        if (! session()->has('user_id')) {
            return redirect('/login');
        }
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect('/');
        }
        $data['cartItems'] = $cartItems;
        $data['title'] = 'Checkout Confirm';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.checkout_confirm', $data);
    }

    public function placeOrder()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/');
        }
        $sub_total = 0;
        foreach ($cart as $item) {
            $sub_total += $item['price'] * $item['qty'];
        }
        $discount = session()->get('discount_value', 0);
        $grand_total = $sub_total - $discount;

        $orderData = [
            'user_id' => session()->get('user_id'),
            'sub_total' => $sub_total,
            'discount' => $discount,
            'grand_total' => $grand_total,
            'create_date' => date('Y-m-d'),
            'status' => 0,
            'checkout_data' => '', // <-- add this
        ];
        $order_id = DB::table('orders')->insertGetId($orderData);
        session()->put('order_id', $order_id);

        foreach ($cart as $item) {
            DB::table('order_details')->insert([
                'item' => $item['name'],
                'type' => $item['type'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'sub_total' => $item['price'] * $item['qty'],
                'order_id' => $order_id,
                'date_added' => date('Y-m-d'),
            ]);
        }

        if (session()->has('user_id')) {
            return redirect('/checkout');
        } else {
            return redirect('/login');
        }
    }

    public function placeOrderGiftcard()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/');
        }

        $sub_total = 0;
        foreach ($cart as $item) {
            $sub_total += $item['price'] * $item['qty'];
        }
        $discount = session()->get('discount_value', 0);
        $grand_total = $sub_total - $discount;

        $orderData = [
            'user_id' => session()->get('user_id'),
            'sub_total' => $sub_total,
            'discount' => $discount,
            'grand_total' => $grand_total,
            'create_date' => date('Y-m-d'),
            'status' => 0,
            'checkout_data' => '', // <-- add this
        ];
        $order_id = DB::table('orders')->insertGetId($orderData);
        session()->put('order_id', $order_id);

        foreach ($cart as $item) {
            DB::table('order_details')->insert([
                'item' => $item['name'],
                'type' => $item['type'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'sub_total' => $item['price'] * $item['qty'],
                'order_id' => $order_id,
                'date_added' => date('Y-m-d'),
            ]);
        }

        if (session()->has('user_id')) {
            return redirect('/checkout-confirm');
        } else {
            return redirect('/login');
        }
    }

    public function checkout_from_data(Request $request)
    {
        $order_id = session()->get('order_id');
        $post = $request->all();
        DB::table('orders')->where('id', $order_id)->update(['checkout_data' => json_encode($post)]);

        return redirect('/checkout-confirm');
    }

    // ---- Add to Cart ----
    public function addToCartPackages(Request $request)
    {
        session()->forget(['order_id', 'gift_cards', 'checkout_type']);
        session()->put('cart', []);

        $package_id = $request->input('package_id');
        $server_qty = $request->input('server_qty');
        $system_qty = $request->input('system_qty');
        $package_type = $request->input('package_type');

        $package = DB::table('packages')->where('id', $package_id)->first();
        if (! $package) {
            return redirect('/cart');
        }

        $server_price = DB::table('package_price')
            ->where('package_id', $package_id)
            ->where('from_qty', '<=', $server_qty)
            ->where('to_qty', '>=', $server_qty)
            ->first();
        $system_price = DB::table('system_price')
            ->where('package_id', $package_id)
            ->where('from_qty', '<=', $system_qty)
            ->where('to_qty', '>=', $system_qty)
            ->first();

        $new_pricess = 0;
        $new_system_pricess = 0;
        if ($package_type == 'Yearly') {
            if ($server_price) {
                $discount_server = $server_price->pack_price * $package->discount / 100;
                $discount_server_price = $server_price->pack_price - $discount_server;
                $new_pricess = $discount_server_price * $server_qty;
            }
            if ($system_price) {
                $discount_system = $system_price->system_price * $package->discount / 100;
                $discount_system_price = $system_price->system_price - $discount_system;
                $new_system_pricess = $discount_system_price * $system_qty;
            }
            session()->put('checkout_type', 'Yearly');
        } else {
            if ($server_price) {
                $new_pricess = $server_price->pack_price * $server_qty;
            }
            if ($system_price) {
                $new_system_pricess = $system_price->system_price * $system_qty;
            }
            session()->put('checkout_type', 'Monthly');
        }

        $cart = session()->get('cart', []);
        if (! empty($server_qty) && $new_pricess > 0) {
            $cart[] = [
                'id' => $package->id.'p',
                'qty' => $server_qty,
                'type' => 'Server',
                'price' => $new_pricess,
                'name' => $package->name,
                'description' => $package->description,
            ];
        }
        if (! empty($system_qty) && $new_system_pricess > 0) {
            $cart[] = [
                'id' => $package->id.'s',
                'qty' => $system_qty,
                'type' => 'Workstation',
                'price' => $new_system_pricess,
                'name' => $package->name,
                'description' => $package->description,
            ];
        }
        session()->put('cart', $cart);

        return redirect('/cart');
    }

    public function addToCartGift(Request $request)
    {
        session()->forget(['order_id', 'gift_cards']);
        session()->put('cart', []);

        $gift_id = $request->input('gift_id');
        $gift = DB::table('gift_card')->where('id', $gift_id)->first();
        if (! $gift) {
            return redirect('/cart');
        }

        session()->put('gift_cards', $gift_id.'-gd');
        $cart = [
            'id' => $gift->id.'gc',
            'qty' => 1,
            'type' => 'Gift Card',
            'price' => $gift->price,
            'name' => $gift->name,
            'description' => $gift->description,
        ];
        session()->put('cart', [$cart]);

        return redirect('/cart');
    }

    // ---- Checkout success ----
    public function checkout_success()
    {
        $data['title'] = 'Order Confirmed';
        $data['description'] = '';
        $data['keywords'] = '';

        return view('frontend.checkout-sucess', $data);
    }

    // ---- Forgot password (redirect to dedicated controller) ----
    public function forgot_password()
    {
        return redirect('/forgot-password');
    }

    // =================================================================
    // NEW: Charge Credit Card (Authorize.Net) - Exact port from CI
    // =================================================================
    public function chargeCreditCard(Request $request)
    {
        // Get order data from session
        $order_id = session()->get('order_id');
        if (! $order_id) {
            return redirect('/checkout-confirm?failed=true');
        }

        $post = $request->all();

        // Validate required fields
        if (empty($post['number']) || empty($post['expiry']) || empty($post['cvc'])) {
            return redirect('/checkout-confirm?failed=true');
        }

        // Update order with user_id if not already set
        $user_id = session()->get('user_id');
        if ($user_id) {
            DB::table('orders')->where('id', $order_id)->update(['user_id' => $user_id]);
        }

        // Get order details
        $order = DB::table('orders')->where('id', $order_id)->first();
        if (! $order) {
            return redirect('/checkout-confirm?failed=true');
        }

        $sub_total = $order->sub_total;
        $discount = $order->discount;
        $amount = $order->grand_total;

        // Get customer info
        $customer = DB::table('user')->where('user_id', $order->user_id)->first();
        if (! $customer) {
            return redirect('/checkout-confirm?failed=true');
        }

        // Get order items to determine type and gift card info
        $order_details = DB::table('order_details')->where('order_id', $order_id)->get();
        $gift_card_data = null;
        if ($order_details->isNotEmpty() && $order_details[0]->type == 'Gift Card') {
            $gift_card_data = DB::table('gift_card')->where('name', $order_details[0]->item)->first();
        }

        $checkout_type = session()->get('checkout_type', 'Monthly');

        // Prepare card details
        $cardNumber = str_replace(' ', '', $post['number']);
        $expiry = str_replace(' ', '', str_replace('/', '-', $post['expiry']));
        $cvc = $post['cvc'];

        // Authorize.Net SDK – exact CI logic
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType;
        $merchantAuthentication->setName(config('services.authorize_net.login_id'));
        $merchantAuthentication->setTransactionKey(config('services.authorize_net.transaction_key'));

        $refId = 'ref'.time();

        $creditCard = new AnetAPI\CreditCardType;
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($expiry);
        $creditCard->setCardCode($cvc);

        $paymentOne = new AnetAPI\PaymentType;
        $paymentOne->setCreditCard($creditCard);

        $orderInfo = new AnetAPI\OrderType;
        $orderInfo->setInvoiceNumber($order_id);
        $orderInfo->setDescription('Order #'.$order_id);

        $customerAddress = new AnetAPI\CustomerAddressType;
        $customerAddress->setFirstName($customer->firstname);
        $customerAddress->setLastName($customer->lastname);
        $customerAddress->setCompany($customer->company ?? '');
        $customerAddress->setAddress($customer->address);
        $customerAddress->setCity($customer->city);
        $customerAddress->setState($customer->state);
        $customerAddress->setZip($customer->zip);
        $customerAddress->setCountry('USA');

        $customerData = new AnetAPI\CustomerDataType;
        $customerData->setType('individual');
        $customerData->setId($order_id);
        $customerData->setEmail($customer->email);

        $duplicateWindowSetting = new AnetAPI\SettingType;
        $duplicateWindowSetting->setSettingName('duplicateWindow');
        $duplicateWindowSetting->setSettingValue('60');

        $transactionRequestType = new AnetAPI\TransactionRequestType;
        $transactionRequestType->setTransactionType('authCaptureTransaction');
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setOrder($orderInfo);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);
        $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);

        $requestObj = new AnetAPI\CreateTransactionRequest;
        $requestObj->setMerchantAuthentication($merchantAuthentication);
        $requestObj->setRefId($refId);
        $requestObj->setTransactionRequest($transactionRequestType);

        $controller = new AnetController\CreateTransactionController($requestObj);
        $environment = config('services.authorize_net.environment') === 'production'
            ? ANetEnvironment::PRODUCTION
            : ANetEnvironment::SANDBOX;
        $response = $controller->executeWithApiResponse($environment);

        if ($response != null) {
            if ($response->getMessages()->getResultCode() == 'Ok') {
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    // Transaction successful – insert into transaction table
                    $transaction_id = $tresponse->getTransId();
                    $auth_code = $tresponse->getAuthCode();

                    DB::table('transaction')->insert([
                        'order_id' => $order_id,
                        'order_type' => $order_details[0]->type ?? '',
                        'checkout_type' => $checkout_type,
                        'transaction_id' => $transaction_id,
                        'user_id' => $order->user_id,
                        'amount' => $amount,
                        'auth_code' => $auth_code,
                    ]);

                    // Update order status
                    DB::table('orders')->where('id', $order_id)->update(['status' => 1]);

                    // Update coupon status if applied
                    if (session()->has('coupon_code')) {
                        $coupon_code = session()->get('coupon_code');
                        DB::table('coupon_checkout')
                            ->where('coupon_number', $coupon_code)
                            ->update(['status' => 1]);
                    }

                    // Generate coupon code for gift card orders
                    if (session()->has('gift_cards') && $gift_card_data) {
                        $length = 7;
                        $coupon_num = strtoupper(substr(md5(time()), 0, $length));
                        $coupon_id = DB::table('coupon_checkout')->insertGetId([
                            'gift_card_id' => $gift_card_data->id,
                            'order_id' => $order_id,
                            'coupon_number' => $coupon_num,
                        ]);
                        // Generate image with watermark (if needed) – we'll skip this for now or implement using Intervention
                    }

                    // Send invoice email to customer and admin
                    $this->sendInvoices($order_id, $transaction_id, $order, $customer, $order_details, $sub_total, $discount, $amount);

                    // Clear cart and session
                    session()->forget(['cart', 'order_id', 'coupon_code', 'discount_value', 'gift_cards', 'checkout_type']);

                    return redirect('/checkout-success');
                } else {
                    // Transaction failed
                    return redirect('/checkout-confirm?failed=true');
                }
            } else {
                // API request failed
                return redirect('/checkout-confirm?failed=true');
            }
        } else {
            return redirect('/checkout-confirm?failed=true');
        }
    }

    // Helper method to send invoices (exact port of CI email logic)
    private function sendInvoices($order_id, $transaction_id, $order, $customer, $order_details, $sub_total, $discount, $amount)
    {
        // Build email HTML (exact CI invoice template)
        $pro_det = '';
        $sr_no = 0;
        foreach ($order_details as $detail) {
            $sr_no++;
            $pro_det .= '<tr>';
            $pro_det .= '<td style="color:black;">'.$sr_no.'</td>';
            $pro_det .= '<td style="color:black;">'.$detail->item.'</td>';
            $pro_det .= '<td style="color:black;">'.$detail->type.'</td>';
            $pro_det .= '<td style="color:black;">'.$detail->qty.'</td>';
            $pro_det .= '<td style="color:black;">$'.number_format($detail->price, 2).'</td>';
            $pro_det .= '<td style="color:black;">$'.number_format($detail->sub_total, 2).'</td>';
            $pro_det .= '</tr>';
        }

        $message = '<!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Invoice</title>
        <style>
            /* All inline styles from CI */
            body { font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif; margin: 0; }
            .container { padding: 20px 40px; }
            .inv-title { padding: 10px; border: 1px solid silver; text-align: center; margin-bottom: 20px; }
            .inv-header table { width: 100%; border-collapse: collapse; border: 1px solid silver; }
            .inv-header table th, .inv-header table td { text-align: right; padding: 8px; border: 1px solid silver; }
            .inv-body table { width: 100%; border: 1px solid silver; border-collapse: collapse; }
            .inv-body table th, .inv-body table td { padding: 10px; border: 1px solid silver; }
            .inv-footer table { width: 30%; float: right; border: 1px solid silver; border-collapse: collapse; }
            .inv-footer table th, .inv-footer table td { padding: 8px; text-align: right; border: 1px solid silver; }
        </style>
        </head>
        <body>
        <div class="container">
            <div class="inv-title">
                <h1 class="no-margin" style="color:black;">Invoice # '.$order_id.'</h1>
            </div>
            <div class="inv-header">
                <table>
                    <tr><th style="text-align:left;color:black;">Date</th><td style="color:black;">'.$order->create_date.'</td></tr>
                    <tr><th style="text-align:left;color:black;">Transaction #</th><td style="color:black;">'.$transaction_id.'</td></tr>
                    <tr><th style="text-align:left;color:black;">Customer Name</th><td style="color:black;">'.$customer->firstname.' '.$customer->lastname.'</td></tr>
                    <tr><th style="text-align:left;color:black;">Address</th><td style="color:black;">'.$customer->address.'</td></tr>
                </table>
            </div>
            <div class="inv-body">
                <table>
                    <thead>
                        <th style="color:black;">Sr #</th>
                        <th style="color:black;">Item</th>
                        <th style="color:black;">Type</th>
                        <th style="color:black;">Quantity</th>
                        <th style="color:black;">Price</th>
                        <th style="color:black;">Sub total</th>
                    </thead>
                    <tbody>
                        '.$pro_det.'
                    </tbody>
                </table>
            </div>
            <div class="inv-footer">
                <table>
                    <tr><th style="color:black;">Sub total</th><td style="color:black;">$'.number_format($sub_total, 2).'</td></tr>
                    <tr><th style="color:black;">Discount</th><td style="color:black;">$'.number_format($discount, 2).'</td></tr>
                    <tr><th style="color:black;">Grand total</th><td style="color:black;">$'.number_format($amount, 2).'</td></tr>
                </table>
            </div>
        </div>
        </body>
        </html>';

        // Send to customer
        $to = $customer->email;
        $subject = 'Order Invoice';
        $from = 'Info@jmor.com';
        Mail::html($message, function ($mail) use ($to, $subject, $from) {
            $mail->to($to)->subject($subject)->from($from);
        });

        // Send to admin
        $admin_email = DB::table('settings')->where('option', 'email')->value('value');
        if ($admin_email) {
            Mail::html($message, function ($mail) use ($admin_email, $subject) {
                $mail->to($admin_email)->subject($subject)->from('noreply@jmor.com');
            });
        }
    }
}
