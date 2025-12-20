<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\AlertService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $store = Store::where('seller_id', auth('web')->user()->id)->first();
        $store = auth('web')->user()->store; //can write because we have made user hasone to one relationship with store in user model
        if (!$store) {
            return redirect()->route('some.route')->with('error', 'Store not found');
        }

        return view('vendorend.store-profile.index', compact('store'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'address'           => ['required', 'string', 'max:255'],
            'logo'              => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'banner'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'email'             => ['required', 'email', 'max:255'],
            'phone'             => ['required', 'string', 'max:15'],
            'short_description' => ['required', 'string', 'max:500'],
            'long_description'  => ['required', 'string', 'max:2000'],
        ]);

        $data = [   // values to update or create
            'logo' => $logoPath ?? '',
            'banner' => $bannerPath ?? '',
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploadFile($request->file('logo'), auth('web')->user()->store?->logo);
        }
        if ($request->hasFile('banner')) {
            $data['banner'] = $this->uploadFile($request->file('banner'), auth('web')->user()->store?->logo);
        }

        Store::updateOrCreate(
            ['seller_id' => auth('web')->user()->id],
            $data
        );

        AlertService::updated();
        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
