<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssessmentForm;
use App\Models\AssessmentFormItem;
use App\Models\GradeScaleStep;
use Illuminate\Support\Facades\Auth;

class RubrikController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $forms = AssessmentForm::with('items')->orderBy('created_at', 'desc')->get();
        $gradeSteps = GradeScaleStep::orderBy('sort_order')->get();
        
        return view('admin.rubrik.index', compact('forms', 'gradeSteps'));
    }
    
    public function createForm(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // Deactivate all other forms
        AssessmentForm::where('is_active', true)->update(['is_active' => false]);
        
        $form = AssessmentForm::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => true,
        ]);
        
        return redirect()->route('admin.rubrik.edit', $form->id)
            ->with('success', 'Form penilaian berhasil dibuat!');
    }
    
    public function edit($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $form = AssessmentForm::with('items')->findOrFail($id);
        $items = $form->items()->orderBy('sort_order')->get();
        
        return view('admin.rubrik.edit', compact('form', 'items'));
    }
    
    public function addItem(Request $request, $formId)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:numeric,boolean,text',
            'weight' => 'required|numeric|min:0|max:100',
        ]);
        
        $form = AssessmentForm::findOrFail($formId);
        
        $nextOrder = $form->items()->max('sort_order') + 1;
        
        AssessmentFormItem::create([
            'form_id' => $formId,
            'label' => $request->label,
            'type' => $request->type,
            'weight' => $request->weight,
            'sort_order' => $nextOrder,
        ]);
        
        return redirect()->back()->with('success', 'Item berhasil ditambahkan!');
    }
    
    public function toggleForm($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $form = AssessmentForm::findOrFail($id);
        
        if ($form->is_active) {
            $form->update(['is_active' => false]);
            $message = 'Form dinonaktifkan!';
        } else {
            // Deactivate all other forms first
            AssessmentForm::where('is_active', true)->update(['is_active' => false]);
            $form->update(['is_active' => true]);
            $message = 'Form diaktifkan!';
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function deleteForm($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $form = AssessmentForm::findOrFail($id);
        $form->delete();
        
        return redirect()->route('admin.rubrik.index')
            ->with('success', 'Form berhasil dihapus!');
    }
    
    public function updateItem(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|in:numeric,boolean,text',
            'weight' => 'nullable|numeric|min:0|max:100',
            'required' => 'boolean'
        ]);
        
        $item = AssessmentFormItem::findOrFail($id);
        $item->update([
            'label' => $request->label,
            'type' => $request->type,
            'weight' => $request->weight ?? 0,
            'required' => $request->has('required')
        ]);
        
        return redirect()->back()->with('success', 'Item berhasil diperbarui');
    }
    
    public function updateOrder(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|integer',
            'updates.*.sort_order' => 'required|integer|min:1'
        ]);
        
        try {
            foreach ($request->updates as $update) {
                AssessmentFormItem::where('id', $update['id'])
                    ->update(['sort_order' => $update['sort_order']]);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    public function deleteItem($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        try {
            $item = AssessmentFormItem::findOrFail($id);
            $item->delete();
            
            return response()->json(['success' => true, 'message' => 'Item berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus item: ' . $e->getMessage()], 500);
        }
    }
}
