<?php
namespace App\Http\Controllers\API;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\ExpenseResource;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;

class ExpenseController extends Controller
{
    /**
     * Get a paginated list of the authenticated user's expenses.
     * Expenses are returned with their related category and payment method,
     * ordered by most recent first.
     */
    public function index(Request $request)
    {
        $expenses = QueryBuilder::for($request->user()->expenses()->getQuery())
            ->allowedFilters([
                AllowedFilter::exact('date'),
                AllowedFilter::callback('from_date', fn($query, $value) => $query->where('date', '>=', $value)),
                AllowedFilter::callback('to_date', fn($query, $value) => $query->where('date', '<=', $value)),
                AllowedFilter::callback('min_amount', fn($query, $value) => $query->where('amount', '>=', $value)),
                AllowedFilter::callback('max_amount', fn($query, $value) => $query->where('amount', '<=', $value)),
            ])
            ->allowedIncludes(['category', 'paymentMethod'])
            ->defaultSort('-date')
            ->allowedSorts(['date', 'amount'])
            ->paginate(10)
            ->appends($request->query());

        return ExpenseResource::collection($expenses);
    }

    /**
     * Store a new expense for the authenticated user.
     * Validates input via StoreExpenseRequest and returns the created resource
     * with related category and payment method.
     */
    public function store(StoreExpenseRequest $request)
    {
        // Create new expense belonging to the authenticated user
        $expense = $request->user()->expenses()->create($request->validated());

        // Return the newly created expense with loaded relationships
        return new ExpenseResource($expense->load(['category', 'paymentMethod']));
    }

    /**
     * Display the details of a single expense.
     * Loads the related category and payment method for context.
     */
    public function show(Expense $expense)
    {
        // Return the specific expense with related data
        return new ExpenseResource($expense->load(['category', 'paymentMethod']));
    }

    /**
     * Update the specified expense with new data.
     * Only allows validated fields via UpdateExpenseRequest.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        // Update the expense with validated data
        $expense->update($request->validated());

        // Return the updated expense with relationships
        return new ExpenseResource($expense->load(['category', 'paymentMethod']));
    }

    /**
     * Delete the specified expense from storage.
     * Returns a 204 No Content response on successful deletion.
     */
    public function destroy(Expense $expense)
    {
        // Remove the expense from the database
        $expense->delete();

        // Return empty response with 204 status code
        return response()->noContent();
    }
}
