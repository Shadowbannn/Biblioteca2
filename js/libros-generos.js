document.addEventListener('DOMContentLoaded', () => {
    const search = document.getElementById('buscar-genero');
    const searchLabel = document.getElementById('buscar-genero-label');
    const list = document.getElementById('lista-generos');
    const count = document.getElementById('generos-contador');
    const selectedList = document.getElementById('generos-etiquetas');
    const emptyResult = document.getElementById('generos-sin-resultados');
    const newGenreInput = document.getElementById('nuevo-genero');
    const addGenreButton = document.getElementById('agregar-genero');
    const pendingGenres = document.getElementById('generos-nuevos-pendientes');
    const newGenreMessage = document.getElementById('genero-nuevo-mensaje');
    const form = search && search.closest('form');

    if (!search || !searchLabel || !list || !count || !selectedList || !emptyResult ||
        !newGenreInput || !addGenreButton || !pendingGenres || !newGenreMessage || !form) {
        return;
    }

    const options = Array.from(list.querySelectorAll('.genero-opcion'));
    search.hidden = false;
    searchLabel.hidden = false;

    const normalize = (value) => value
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLocaleLowerCase();

    const renderSelected = () => {
        const selected = options.filter((option) => option.querySelector('input').checked);
        const pending = Array.from(pendingGenres.querySelectorAll('[data-genero-nuevo]'));
        const total = selected.length + pending.length;
        count.textContent = `${total} ${total === 1 ? 'género seleccionado' : 'géneros seleccionados'}`;
        selectedList.replaceChildren();

        const addTag = (nameText, removeSelected) => {
            const tag = document.createElement('span');
            tag.className = 'genero-etiqueta';

            const name = document.createElement('span');
            name.textContent = nameText;
            tag.appendChild(name);

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.textContent = '×';
            remove.setAttribute('aria-label', `Quitar ${name.textContent}`);
            remove.addEventListener('click', () => {
                removeSelected();
            });
            tag.appendChild(remove);
            selectedList.appendChild(tag);
        };

        selected.forEach((option) => {
            const checkbox = option.querySelector('input');
            addTag(option.querySelector('span').textContent, () => {
                checkbox.checked = false;
                checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                checkbox.focus();
            });
        });

        pending.forEach((input) => {
            addTag(input.value, () => {
                input.remove();
                renderSelected();
                newGenreInput.focus();
            });
        });
    };

    const filterOptions = () => {
        const query = normalize(search.value.trim());
        let visibleCount = 0;

        options.forEach((option) => {
            const matches = normalize(option.querySelector('span').textContent).includes(query);
            option.hidden = !matches;
            if (matches) visibleCount += 1;
        });

        emptyResult.hidden = visibleCount > 0;
    };

    const addNewGenre = () => {
        const name = newGenreInput.value.trim().replace(/\s+/g, ' ');
        if (!name) {
            newGenreMessage.textContent = 'Escribe el nombre del género que quieres agregar.';
            newGenreInput.focus();
            return false;
        }
        if (name.includes(',')) {
            newGenreMessage.textContent = 'Agrega un solo género por vez; no uses comas.';
            newGenreInput.focus();
            return false;
        }
        if (/^sin\s+g[eé]nero$/iu.test(name)) {
            newGenreMessage.textContent = '“Sin género” representa libros sin géneros asociados.';
            newGenreInput.focus();
            return false;
        }

        const normalizedName = normalize(name);
        const existing = options.find((option) =>
            normalize(option.querySelector('span').textContent) === normalizedName
        );
        if (existing) {
            const checkbox = existing.querySelector('input');
            checkbox.checked = true;
            search.value = '';
            filterOptions();
            renderSelected();
            newGenreMessage.textContent = `${existing.querySelector('span').textContent} ya existe y quedó seleccionado.`;
            newGenreInput.value = '';
            checkbox.focus();
            return true;
        }

        const duplicatePending = Array.from(pendingGenres.querySelectorAll('[data-genero-nuevo]'))
            .some((input) => normalize(input.value) === normalizedName);
        if (duplicatePending) {
            newGenreMessage.textContent = 'Ese género ya está seleccionado.';
            newGenreInput.value = '';
            renderSelected();
            return true;
        }

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'generos_nuevos[]';
        hidden.value = name;
        hidden.dataset.generoNuevo = '';
        pendingGenres.appendChild(hidden);
        newGenreInput.value = '';
        newGenreMessage.textContent = `${name} quedó seleccionado y se guardará con el libro.`;
        renderSelected();
        return true;
    };

    search.addEventListener('input', filterOptions);
    list.addEventListener('change', renderSelected);
    addGenreButton.addEventListener('click', addNewGenre);
    newGenreInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            addNewGenre();
        }
    });
    form.addEventListener('submit', (event) => {
        if (newGenreInput.value.trim() !== '' && !addNewGenre()) {
            event.preventDefault();
        }
    });
    filterOptions();
    renderSelected();
});
