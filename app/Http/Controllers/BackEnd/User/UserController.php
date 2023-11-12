<?php

namespace App\Http\Controllers\BackEnd\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $searchKey = null;

        if ($request->filled('info')) {
            $searchKey = $request['info'];
        }

        $users = User::when($searchKey, function ($query, $searchKey) {
            return $query->where('username', 'like', '%' . $searchKey . '%')
                ->orWhere('email', 'like', '%' . $searchKey . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('backend.end-user.user.index', compact('users'));
    }

    public function updateEmailStatus(Request $request, $id)
    {
        $user = User::find($id);

        if ($request['email_status'] == 'verified') {
            $user->update([
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $user->update([
                'email_verified_at' => NULL
            ]);
        }

        $request->session()->flash('success', 'Email status updated successfully!');

        return redirect()->back();
    }

    public function updateAccountStatus(Request $request, $id)
    {
        $user = User::find($id);

        if ($request['account_status'] == 1) {
            $user->update(['status' => 1]);
        } else {
            $user->update(['status' => 0]);
        }

        $request->session()->flash('success', 'Account status updated successfully!');

        return redirect()->back();
    }

    public function show($id)
    {
        $userInfo = User::find($id);
        $information['userInfo'] = $userInfo;

        return view('backend.end-user.user.details', $information);
    }

    public function changePassword($id)
    {
        $userInfo = User::find($id);

        return view('backend.end-user.user.change-password', compact('userInfo'));
    }

    public function updatePassword(Request $request, $id)
    {
        $rules = [
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $user = User::find($id);

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        $request->session()->flash('success', 'Password updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        // delete attachments & invoices
        $enrolments = $user->courseEnrol()->get();

        if (count($enrolments) > 0) {
            foreach ($enrolments as $enrolment) {
                @unlink('file/attachments/' . $enrolment->attachment);
                @unlink('file/invoices/' . $enrolment->invoice);
            }
        }

        // delete quiz's score of this user
        $quizScores = $user->quizScore()->get();

        if (count($quizScores) > 0) {
            foreach ($quizScores as $quizScore) {
                $quizScore->delete();
            }
        }

        // delete user image
        @unlink('img/users/' . $user->image);

        $user->delete();

        return redirect()->back()->with('success', 'User info deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $user = User::find($id);

            // delete attachments & invoices
            $enrolments = $user->courseEnrol()->get();

            if (count($enrolments) > 0) {
                foreach ($enrolments as $enrolment) {
                    @unlink('file/attachments/' . $enrolment->attachment);
                    @unlink('file/invoices/' . $enrolment->invoice);
                }
            }

            // delete quiz's score of this user
            $quizScores = $user->quizScore()->get();

            if (count($quizScores) > 0) {
                foreach ($quizScores as $quizScore) {
                    $quizScore->delete();
                }
            }

            // delete user image
            @unlink('img/users/' . $user->image);

            $user->delete();
        }

        $request->session()->flash('success', 'Users info deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
