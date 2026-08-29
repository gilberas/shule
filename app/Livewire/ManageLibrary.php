<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\BookIssuance;
use App\Models\Student;

class ManageLibrary extends Component
{
    public $title = '';
    public $author = '';
    public $isbn = '';
    public $category = '';
    public $publisher;
    public $publishedYear;
    public $totalCopies = 1;
    public $availableCopies = 1;
    public $description;

    public $editBookId;
    public $editTitle = '';
    public $editAuthor = '';
    public $editIsbn = '';
    public $editCategory = '';
    public $editPublisher;
    public $editPublishedYear;
    public $editTotalCopies = 1;
    public $editAvailableCopies = 1;
    public $editDescription;

    public $studentId;
    public $issueDate;
    public $dueDate;
    public $status = 'issued';
    public $notes;

    public $editIssuanceId;
    public $editStudentId;
    public $editBookIdIssuance;
    public $editIssuedDate;
    public $editDueDate;
    public $editReturnDate;
    public $editStatus = 'issued';
    public $editNotes;

    public $issueMode = 'issue';

    public function render()
    {
        return view('livewire.manage-library', [
            'books' => Book::with('issuances')->get(),
            'students' => Student::with(['classLevel', 'stream', 'academicYear'])->get(),
        ]);
    }

    public function storeBook()
    {
        Book::create([
            'title' => $this->title,
            'author' => $this->author,
            'isbn' => $this->isbn,
            'category' => $this->category,
            'publisher' => $this->publisher,
            'published_year' => $this->publishedYear,
            'total_copies' => $this->totalCopies,
            'available_copies' => $this->availableCopies,
            'description' => $this->description,
        ]);

        $this->reset(['title', 'author', 'isbn', 'category', 'publisher', 'publishedYear', 'totalCopies', 'availableCopies', 'description']);
        $this->emit('alert', 'Book added successfully!');
    }

    public function editBook($id)
    {
        $book = Book::find($id);
        $this->editBookId = $book->id;
        $this->editTitle = $book->title;
        $this->editAuthor = $book->author;
        $this->editIsbn = $book->isbn;
        $this->editCategory = $book->category;
        $this->editPublisher = $book->publisher;
        $this->editPublishedYear = $book->published_year;
        $this->editTotalCopies = $book->total_copies;
        $this->editAvailableCopies = $book->available_copies;
        $this->editDescription = $book->description;
    }

    public function updateBook()
    {
        $book = Book::find($this->editBookId);
        $book->update([
            'title' => $this->editTitle,
            'author' => $this->editAuthor,
            'isbn' => $this->editIsbn,
            'category' => $this->editCategory,
            'publisher' => $this->editPublisher,
            'published_year' => $this->editPublishedYear,
            'total_copies' => $this->editTotalCopies,
            'available_copies' => $this->editAvailableCopies,
            'description' => $this->editDescription,
        ]);

        $this->resetBookInput();
        $this->emit('alert', 'Book updated successfully!');
    }

    public function deleteBook($id)
    {
        Book::find($id)->delete();
        $this->emit('alert', 'Book deleted successfully!');
    }

    public function resetBookInput()
    {
        $this->editBookId = null;
        $this->editTitle = '';
        $this->editAuthor = '';
        $this->editIsbn = '';
        $this->editCategory = '';
        $this->editPublisher = null;
        $this->editPublishedYear = null;
        $this->editTotalCopies = 1;
        $this->editAvailableCopies = 1;
        $this->editDescription = '';
    }

    public function issueBook()
    {
        // Check if book has available copies
        $book = Book::find($this->studentId !== null ? null : 0); // This needs fixing
        
        BookIssuance::create([
            'book_id' => isset($this->editBookIdIssuance) ? $this->editBookIdIssuance : 0,
            'student_id' => $this->studentId,
            'issued_date' => $this->issueDate,
            'due_date' => $this->dueDate,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        // Update available copies
        if (isset($this->editBookIdIssuance)) {
            $book = Book::find($this->editBookIdIssuance);
            $book->available_copies = max(0, $book->available_copies - 1);
            $book->save();
        }

        $this->reset(['studentId', 'issueDate', 'dueDate', 'status', 'notes', 'editBookIdIssuance']);
        $this->emit('alert', 'Book issued successfully!');
    }

    public function returnBook($id)
    {
        $issuance = BookIssuance::find($id);
        $issuance->return_date = now()->toDateString();
        $issuance->status = 'returned';
        $issuance->save();

        // Update available copies
        $book = Book::find($issuance->book_id);
        $book->available_copies++;
        $book->save();

        $this->emit('alert', 'Book returned successfully!');
    }

    public function editIssuance($id)
    {
        $issuance = BookIssuance::find($id);
        $this->editIssuanceId = $issuance->id;
        $this->editStudentId = $issuance->student_id;
        $this->editBookIdIssuance = $issuance->book_id;
        $this->editIssuedDate = $issuance->issued_date;
        $this->editDueDate = $issuance->due_date;
        $this->editReturnDate = $issuance->return_date;
        $this->editStatus = $issuance->status;
        $this->editNotes = $issuance->notes;
    }

    public function updateIssuance()
    {
        $issuance = BookIssuance::find($this->editIssuanceId);
        $issuance->update([
            'student_id' => $this->editStudentId,
            'book_id' => $this->editBookIdIssuance,
            'issued_date' => $this->editIssuedDate,
            'due_date' => $this->editDueDate,
            'return_date' => $this->editReturnDate,
            'status' => $this->editStatus,
            'notes' => $this->editNotes,
        ]);

        // Update available copies based on status change
        $book = Book::find($issuance->book_id);
        if ($issuance->status === 'returned') {
            $book->available_copies++;
        } elseif ($issuance->status === 'issued' && $this->editStatus ?? '' === 'issued') {
            $book->available_copies = max(0, $book->available_copies - 1);
        }
        $book->save();

        $this->resetIssuanceInput();
        $this->emit('alert', 'Book issuance updated successfully!');
    }

    public function resetIssuanceInput()
    {
        $this->editIssuanceId = null;
        $this->editStudentId = null;
        $this->editBookIdIssuance = null;
        $this->editIssuedDate = null;
        $this->editDueDate = null;
        $this->editReturnDate = null;
        $this->editStatus = 'issued';
        $this->editNotes = '';
    }
}