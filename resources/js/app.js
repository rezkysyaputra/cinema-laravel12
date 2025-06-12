import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

function movieApp() {
    return {
        // Basic State
        userName: "Movie Lover",
        activeTab: "home",
        searchQuery: "",
        selectedMovie: null,

        // Computed Properties - Auto Update
        get filteredCurrentMovies() {
            if (!this.searchQuery) return this.currentMovies;
            return this.currentMovies.filter(
                (movie) =>
                    movie.title
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase()) ||
                    movie.genre
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase())
            );
        },

        get filteredComingMovies() {
            if (!this.searchQuery) return this.comingMovies;
            return this.comingMovies.filter(
                (movie) =>
                    movie.title
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase()) ||
                    movie.genre
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase())
            );
        },

        // Methods
        openMovieDetail(movie) {
            this.selectedMovie = movie;
        },

        closeMovieDetail() {
            this.selectedMovie = null;
        },

        bookMovie() {
            alert(`Booking ticket for: ${this.selectedMovie.title}`);
            this.closeMovieDetail();
        },
    };
}
