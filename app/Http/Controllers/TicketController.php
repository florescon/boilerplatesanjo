<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

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
