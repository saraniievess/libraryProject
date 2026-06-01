document.addEventListener(
    "readystatechange",
    (event) => {

        if (
            document.readyState !== "complete"
        ) {
            return;
        }

        const table = document.getElementById("book_table");
        const template = document.getElementById("book_table_row");
        let ui = new book_ui(table, template);
        ui.init();
    },
    true
);

class book_ui {
    constructor(
        _table,
        _template
    ) {

        this.table = _table;
        this.template = _template;
        this.books = [];
    }

    init() {
        this.load_books()
            .then(
                () => {
                    this.draw_books(this.books);
                }
            );
    }

    load_books() {

        const uri = "backend/get_books.php";

        const data = { method: "GET" };

        return fetch(uri, data)
            .then(
                (_res) => {
                    if (
                        _res.status !== 200
                    ) {
                        throw new Error("unexpected status code");
                    }
                    return _res.json();
                }
            )
            .then(
                (_res) => {
                    this.books = _res.data;
                }
            )
            .catch(
                (_err) => {
                    console.error(_err);
                    alert("Ha ocurrido un error");
                }
            );
    }

    draw_books(_books) {
        let tbody = this.table.querySelector("tbody");
        tbody.innerHTML = "";

        _books.forEach(
            (_book) => {
                this.draw_book(_book, tbody);
            }
        );
    }

    draw_book(_book, _tbody) {
        let clone = document.importNode(this.template.content, true);
        let td_id = clone.querySelector("td[data-content='id']");
        let td_title = clone.querySelector("td[data-content='title']");
        let td_author = clone.querySelector("td[data-content='author']");
        let td_house = clone.querySelector("td[data-content='house']");
        let td_page_count = clone.querySelector("td[data-content='page_count']");
        let td_genre = clone.querySelector("td[data-content='genre']");
        let td_reviews = clone.querySelector("td[data-content='reviews']");

        td_id.innerText = _book.id;
        td_title.innerText = _book.title;
        td_author.innerText = _book.author;
        td_house.innerText = _book.house;
        td_page_count.innerText = _book.page_count;
        td_genre.innerText = _book.genre;
        let review_link = document.createElement("a");
        review_link.href = "resenas_libro.php?title=" + encodeURIComponent(_book.title);
        review_link.innerText = "Reseñas";
        td_reviews.appendChild(review_link);
        _tbody.appendChild(clone);
    }
}