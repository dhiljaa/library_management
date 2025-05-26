<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'user'        => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ],
            'book'        => [
                'id'     => $this->book->id,
                'title'  => $this->book->title,
                'author' => $this->book->author,
            ],
            'loan_date'   => $this->loan_date,
            'due_date'    => $this->due_date,
            'return_date' => $this->return_date,
            'status'      => $this->status,
            'created_at'  => $this->created_at->toDateTimeString(),
            'updated_at'  => $this->updated_at->toDateTimeString(),
        ];
    }
}
