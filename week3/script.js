const API_URL = 'http://localhost:3000/movies';

const movieListDiv = document.getElementById('movie-list');
const searchInput = document.getElementById('search-input');
const form = document.getElementById('add-movie-form');

let allMovies = [];

// ✅ Render Movies
function renderMovies(moviesToDisplay) {
    movieListDiv.innerHTML = '';

    if (moviesToDisplay.length === 0) {
        movieListDiv.innerHTML = '<p>No movies found matching your criteria.</p>';
        return;
    }

    moviesToDisplay.forEach(movie => {
        const movieElement = document.createElement('div');
        movieElement.classList.add('movie-item');

        const info = document.createElement('p');
        info.innerHTML = `<strong>${movie.title}</strong> (${movie.year}) - ${movie.genre}`;

        const btnGroup = document.createElement('div');
        btnGroup.classList.add('btn-group');

        const editBtn = document.createElement('button');
        editBtn.textContent = 'Edit';
        editBtn.addEventListener('click', () => {
            editMoviePrompt(movie.id, movie.title, movie.year, movie.genre);
        });

        const deleteBtn = document.createElement('button');
        deleteBtn.textContent = 'Delete';
        deleteBtn.classList.add('delete-btn');
        deleteBtn.addEventListener('click', () => {
            deleteMovie(movie.id);
        });

        btnGroup.appendChild(editBtn);
        btnGroup.appendChild(deleteBtn);

        movieElement.appendChild(info);
        movieElement.appendChild(btnGroup);
        movieListDiv.appendChild(movieElement);
    });
}


// ✅ Fetch Movies (READ)
function fetchMovies() {
    fetch(API_URL)
        .then(response => response.json())
        .then(movies => {
            allMovies = movies;
            renderMovies(allMovies);
        })
        .catch(error => console.error('Error fetching movies:', error));
}

fetchMovies(); // Initial Load

// ✅ Search Feature
searchInput.addEventListener('input', function () {
    const searchTerm = searchInput.value.toLowerCase();

    const filteredMovies = allMovies.filter(movie =>
        movie.title.toLowerCase().includes(searchTerm) ||
        movie.genre.toLowerCase().includes(searchTerm)
    );

    renderMovies(filteredMovies);
});

// ✅ Add Movie (CREATE)
form.addEventListener('submit', function (event) {
    event.preventDefault();

    const newMovie = {
        title: document.getElementById('title').value,
        genre: document.getElementById('genre').value,
        year: parseInt(document.getElementById('year').value)
    };

    fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(newMovie),
    })
        .then(response => response.json())
        .then(() => {
            form.reset();
            fetchMovies();
        })
        .catch(error => console.error('Error adding movie:', error));
});

// ✅ Edit Movie
function editMoviePrompt(id, currentTitle, currentYear, currentGenre) {
    const newTitle = prompt('Enter new Title:', currentTitle);
    const newYearStr = prompt('Enter new Year:', currentYear);
    const newGenre = prompt('Enter new Genre:', currentGenre);

    if (!newTitle || !newYearStr || !newGenre) {
        alert("Edit cancelled or invalid input.");
        return;
    }

    const updatedMovie = {
        title: newTitle,
        year: Number(newYearStr),
        genre: newGenre
    };

    updateMovie(id, updatedMovie);
}


// ✅ Update Movie (UPDATE)
function updateMovie(movieId, updatedMovieData) {
    fetch(`${API_URL}/${movieId}`, {
        method: 'PATCH',   // ✅ PATCH instead of PUT
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedMovieData),
    })
        .then(response => {
            if (!response.ok) throw new Error("Update failed");
            return response.json();
        })
        .then(() => fetchMovies())
        .catch(error => console.error('Error updating movie:', error));
}


// ✅ Delete Movie (DELETE)
function deleteMovie(movieId) {
    if (!confirm("Are you sure you want to delete this movie?")) return;

    fetch(`${API_URL}/${movieId}`, {
        method: 'DELETE',
    })
        .then(() => fetchMovies())
        .catch(error => console.error('Error deleting movie:', error));
}
