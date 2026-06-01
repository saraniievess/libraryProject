document.addEventListener(
    "readystatechange",
    (event) => {
        if (
            document.readyState !== "complete"
        ) {
            return;
        }

        const table = document.getElementById("review_user_table");
        const template = document.getElementById("review_user_table_row");
        let ui = new review_user_ui(table, template);

        ui.init();
    },
    true
);

class review_user_ui {

    constructor(
        _table,
        _template
    ) {
        this.table = _table;
        this.template = _template;
        this.reviews = [];
    }

    init() {
        this.load_reviews()
            .then(
                () => {
                    this.draw_reviews(this.reviews);
                }
            );
    }

    load_reviews() {

        let params = new URLSearchParams(window.location.search);
        let username = params.get("username");
        const uri = "backend/get_reviews_user.php?username=" + encodeURIComponent(username);

        return fetch(uri, { method: "GET" }
        )
            .then(
                (_res) => {
                    return _res.json();
                }
            )
            .then(
                (_res) => {
                    this.reviews = _res.data;
                }
            );
    }

    draw_reviews(
        _reviews
    ) {
        let tbody = this.table.querySelector("tbody");
        tbody.innerHTML = "";

        _reviews.forEach(
            (_review) => {
                this.draw_review(_review, tbody
                );
            }
        );
    }

    draw_review(
        _review,
        _tbody
    ) {
        let clone = document.importNode(this.template.content, true);

        clone.querySelector("[data-content='id']").innerText = _review.id;
        clone.querySelector("[data-content='title']").innerText = _review.title;
        clone.querySelector("[data-content='date']").innerText = _review.date;
        clone.querySelector("[data-content='ranking']").innerText = "⭐".repeat(_review.ranking);
        clone.querySelector("[data-content='info']").innerText = _review.info;

        _tbody.appendChild(clone);
    }
}