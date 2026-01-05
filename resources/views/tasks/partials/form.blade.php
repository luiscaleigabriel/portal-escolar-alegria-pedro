<div class="mb-3">
    <label for="title" class="form-label">Título</label>
    <input type="text" name="name" value="{{ $task->name ?? '' }}" id="title" class="form-control" required>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descrição</label>
    <textarea name="description" id="description" rows="4" class="form-control">{{ $task->description ?? '' }}</textarea>
</div>

<div class="mb-3">
    <label for="priority" class="form-label">Prioridade</label>
    <select name="priority" id="priority" class="form-select" required>
        <option {{ isset($task) ? $task->priority == 'a' ? 'selected' : '' : ''}} value="a">Alta</option>
        <option {{ isset($task) ? $task->priority == 'm' ? 'selected' : '' : ''}} value="m">Média</option>
        <option {{ isset($task) ? $task->priority == 'b' ? 'selected' : '' : ''}} value="b">Baixa</option>
    </select>
</div>

<div class="mb-3">
    <label for="status" class="form-label">Estado</label>
    <select name="status" id="status" class="form-select">
        <option {{ isset($task) ? $task->status == 'p' ? 'selected' : '' : ''}} value="p">Pendente</option>
        <option {{ isset($task) ? $task->status == 'd' ? 'selected' : '' : ''}} value="d">Concluído</option>
    </select>
</div>
