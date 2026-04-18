<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless(auth()->user()?->isAdmin(), 403);
            return $next($request);
        });
    }

    // ── Dashboard ─────────────────────────────────────────────────
    public function dashboard(): View
    {
        $stats = [
            'total_properties'    => Property::count(),
            'pending_properties'  => Property::pending()->count(),
            'approved_properties' => Property::approved()->count(),
            'total_agents'        => User::where('role', User::ROLE_AGENT)->count(),
            'pending_agents'      => User::where('role', User::ROLE_AGENT)
                                         ->where('is_approved', false)->count(),
            'total_clients'       => User::where('role', User::ROLE_CLIENT)->count(),
            'total_inquiries'     => Inquiry::count(),
            'new_inquiries'       => Inquiry::where('status', Inquiry::STATUS_NEW)->count(),
        ];

        $recentProperties = Property::pending()
            ->with('agent')
            ->latest()
            ->limit(5)
            ->get();

        $pendingAgents = User::where('role', User::ROLE_AGENT)
            ->where('is_approved', false)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProperties', 'pendingAgents'));
    }

    // ── Agent Management ──────────────────────────────────────────
    public function agents(Request $request): View
    {
        $query = User::where('role', User::ROLE_AGENT);

        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }

        $agents = $query->withCount('properties')->latest()->paginate(20);

        return view('admin.agents.index', compact('agents'));
    }

    public function approveAgent(User $user): RedirectResponse
    {
        abort_unless($user->isAgent(), 400);
        $user->update(['is_approved' => true]);

        // TODO: send approval notification email
        return back()->with('success', "Agent {$user->name} approved.");
    }

    public function rejectAgent(User $user): RedirectResponse
    {
        abort_unless($user->isAgent(), 400);
        $user->update(['is_approved' => false]);

        return back()->with('success', "Agent {$user->name} access revoked.");
    }

    public function destroyAgent(User $user): RedirectResponse
    {
        abort_unless($user->isAgent(), 400);
        $user->delete();

        return redirect()->route('admin.agents')->with('success', 'Agent removed.');
    }

    // ── Property Moderation ───────────────────────────────────────
    public function properties(Request $request): View
    {
        $query = Property::with('agent')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $properties = $query->paginate(20);

        return view('admin.properties.index', compact('properties'));
    }

    public function showProperty(Property $property): View
    {
        $property->load('agent', 'inquiries');
        return view('admin.properties.show', compact('property'));
    }

    public function approveProperty(Property $property): RedirectResponse
    {
        $property->update([
            'status'           => Property::STATUS_APPROVED,
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Property approved and now live.');
    }

    public function rejectProperty(Request $request, Property $property): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $property->update([
            'status'           => Property::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Property rejected with reason.');
    }

    public function featureProperty(Property $property): RedirectResponse
    {
        $property->update(['is_featured' => ! $property->is_featured]);

        $label = $property->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Property {$label}.");
    }

    public function destroyProperty(Property $property): RedirectResponse
    {
        $property->delete();
        return redirect()->route('admin.properties')->with('success', 'Property deleted.');
    }

    // ── User list ─────────────────────────────────────────────────
    public function users(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->withCount('properties')->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }
}
