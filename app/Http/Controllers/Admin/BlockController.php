<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\User;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    /**
     * Display a listing of blocks.
     */
    public function index()
    {
        $blocks = Block::withCount('users')->orderBy('name')->paginate(15);
        
        $stats = [
            'total_blocks' => Block::count(),
            'blocks_with_treasurers' => Block::has('users')->count(),
            'total_students' => User::where('role', 'student')->count(),
        ];

        return view('admin.blocks.index', compact('blocks', 'stats'));
    }

    /**
     * Show the form for creating a new block.
     */
    public function create()
    {
        $treasurers = User::where('role', 'treasurer')->whereNull('block_id')->get();
        return view('admin.blocks.create', compact('treasurers'));
    }

    /**
     * Store a newly created block in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:blocks'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Block::create($validated);

        return redirect()->route('admin.blocks.index')
            ->with('success', 'Block created successfully!');
    }

    /**
     * Display the specified block.
     */
    public function show(Block $block)
    {
        $block->load(['users' => function($query) {
            $query->orderBy('name');
        }]);
        
        $students = $block->users()->where('role', 'student')->get();
        $treasurer = $block->users()->where('role', 'treasurer')->first();
        
        return view('admin.blocks.show', compact('block', 'students', 'treasurer'));
    }

    /**
     * Show the form for editing the specified block.
     */
    public function edit(Block $block)
    {
        $treasurers = User::where('role', 'treasurer')
            ->where(function($query) use ($block) {
                $query->whereNull('block_id')
                    ->orWhere('block_id', $block->id);
            })->get();
            
        return view('admin.blocks.edit', compact('block', 'treasurers'));
    }

    /**
     * Update the specified block in storage.
     */
    public function update(Request $request, Block $block)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:blocks,name,' . $block->id],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $block->update($validated);

        return redirect()->route('admin.blocks.index')
            ->with('success', 'Block updated successfully!');
    }

    /**
     * Remove the specified block from storage.
     */
    public function destroy(Block $block)
    {
        // Check if block has users
        if ($block->users()->count() > 0) {
            return redirect()->route('admin.blocks.index')
                ->with('error', 'Cannot delete block with assigned users. Please reassign users first.');
        }

        $block->delete();

        return redirect()->route('admin.blocks.index')
            ->with('success', 'Block deleted successfully!');
    }
}
