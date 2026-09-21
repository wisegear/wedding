<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveHomepageContentRequest;
use App\Http\Requests\SaveOrderOfServiceItemRequest;
use App\Http\Requests\SaveOrderOfServiceRequest;
use App\Models\OrderOfService;
use App\Models\OrderOfServiceItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderOfServiceController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.order-of-service.index', [
            'orderOfService' => OrderOfService::settings(),
            'items' => OrderOfServiceItem::query()->orderBy('starts_at')->orderBy('id')->get(),
            'editingItem' => $request->integer('edit') ? OrderOfServiceItem::query()->findOrFail($request->integer('edit')) : null,
        ]);
    }

    public function store(SaveOrderOfServiceItemRequest $request): RedirectResponse
    {
        OrderOfServiceItem::query()->create($request->validated());

        return redirect()->route('admin.order-of-service.index')->with('status', 'Order of service item added.');
    }

    public function update(SaveOrderOfServiceItemRequest $request, OrderOfServiceItem $orderOfServiceItem): RedirectResponse
    {
        $orderOfServiceItem->update($request->validated());

        return redirect()->route('admin.order-of-service.index')->with('status', 'Order of service item updated.');
    }

    public function saveSettings(SaveOrderOfServiceRequest $request): RedirectResponse
    {
        OrderOfService::query()->updateOrCreate(['id' => 1], $request->validated());

        return redirect()->route('admin.order-of-service.index')->with('status', 'Heading and tagline updated.');
    }

    public function confirmDelete(OrderOfServiceItem $orderOfServiceItem): View
    {
        return view('admin.order-of-service.delete', ['item' => $orderOfServiceItem]);
    }

    public function destroy(OrderOfServiceItem $orderOfServiceItem): RedirectResponse
    {
        $orderOfServiceItem->delete();

        return redirect()->route('admin.order-of-service.index')->with('status', 'Order of service item deleted.');
    }

    public function saveHomepage(SaveHomepageContentRequest $request): RedirectResponse
    {
        OrderOfService::query()->updateOrCreate(['id' => 1], $request->validated());

        return redirect()->route('admin.order-of-service.index')->with('status', 'Homepage text updated.');
    }
}
