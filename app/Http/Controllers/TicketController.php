<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Assignment;
use App\Models\AssignmentHistory;
use Illuminate\Http\Request;
use App\Domains\Auth\Models\User;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.ticket.index');
    }

    public function history(User $user)
    {
        // $userUpdate = AssignmentHistory::where('user_id', $user->id)->get();
        // foreach($userUpdate as $assignment){

        //     $assignment->timestamps = FALSE;

        //     $assignment->update([
        //         'ticket_id' => $assignment->assignment->ticket_id,
        //     ]);
        // }

        return view('backend.ticket.history', compact('user'));
    }

    public function deleted()
    {
        return view('backend.ticket.deleted');
    }

    public function destroy(Ticket $ticket)
    {
        if($ticket->id){
            $ticket->delete();
        }

        return redirect()->back()->withFlashSuccess(__('The ticket was successfully deleted.'));
    }
}
