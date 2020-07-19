'use strict';

const theContainer = document.querySelector('.container'),
	addBlock = document.querySelector('#add-task-container'),
	addBtn = document.querySelector('.add-btn'),
	addBackBtn = document.querySelector('#add-back-btn');

// Clicking on Add hides all current TASKS
// shows only addBlock
addBtn.onclick = function () {
	theContainer.style.display = 'none';
	addBlock.style.display = 'block';
};

// Clicking on  Back hides the addBlock
// and shows all current Tasks
addBackBtn.onclick = function () {
	theContainer.style.display = 'block';
	addBlock.style.display = 'none';
	addBtn.style.display = 'block';
};

theContainer.addEventListener('click', event => {
	let target = event.target;

	if (target.classList.contains('status')) {
		// Clicking on the checkbox updates
		// Task status in the DB
		if (target.checked) {
			window.open(
				`todo_logic/update_status.php?status=1&id=${target.id}`,
				'_self'
			);
		} else {
			window.open(
				`todo_logic/update_status.php?status=0&id=${target.id}`,
				'_self'
			);
		}
	} else if (target.classList.contains('edit-btn')) {
		// Hiding current Task
		// Creating a temporary edit panel with all the corresponding information

		let idNumber = target.id.substring(
			target.id.indexOf('-') + 1,
			target.id.length
		);
		let targetContainer = document.querySelector(`#task-container-${idNumber}`);
		let targetTitle = document.querySelector(`#title-${idNumber}`).innerHTML;
		let targetDescription = document.querySelector(`#description-${idNumber}`)
			.innerHTML;
		let editBlock = document.createElement('div');
		editBlock.id = `edit-block-${idNumber}`;
		editBlock.classList.toggle('hidden-block');
		editBlock.style.display = 'block';

		editBlock.innerHTML = `<h3>Edit</h3>
            <form action="todo_logic/edit_task.php" method="POST">
                <div>
                    <label for="title">Title</label><br>
                    <input id="title-${idNumber}" name="title" type="text" value="${targetTitle}" required>
                </div>
                <div>
                    <label for="taskDescription">Description</label><br>
                    <textarea id="taskDescription-${idNumber}" name="taskDescription">${targetDescription}</textarea>
                </div>
                <div>
                    <button id="back-${idNumber}" type="button" class="button back-btn">Back</button>
                    <button id="delete-${idNumber}" name="delete-task" value="${idNumber}" type="submit" class="button delete-btn">Delete</button>
                    <button id="save-${idNumber}" name="edit-task" value="${idNumber}" type="submit" class="button add-submit-btn">Save</button>
                </div>
			</form>`;

		targetContainer.before(editBlock);
		targetContainer.style.display = 'none';
	} else if (target.classList.contains('back-btn')) {
		// Destroying the temporary edit panel
		// Revealing the hidden task item

		let idNumber = target.id.substring(
			target.id.indexOf('-') + 1,
			target.id.length
		);

		document.querySelector(`#task-container-${idNumber}`).style.display =
			'flex';
		document.querySelector(`#edit-block-${idNumber}`).remove();
	}
});
