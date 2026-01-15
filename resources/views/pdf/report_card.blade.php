<h2 align="center">BOLETIM ESCOLAR</h2>

<b>Aluno:</b> {{ $student->user->name }}<br>
<b>Matrícula:</b> {{ $student->registration_number }}<br>
<b>Turma:</b> {{ $student->turma->name }}<br><br>

<h3>Notas</h3>
<table border="1" width="100%">
    <tr>
        <th>Disciplina</th>
        <th>Nota</th>
        <th>Trimestre</th>
    </tr>
    @foreach ($grades as $g)
        <tr>
            <td>{{ $g->subject->name }}</td>
            <td>{{ $g->value }}</td>
            <td>{{ $g->term }}</td>
        </tr>
    @endforeach
</table>

<h3>Faltas</h3>
<table border="1" width="100%">
    <tr>
        <th>Disciplina</th>
        <th>Data</th>
        <th>Justificada</th>
    </tr>
    @foreach ($absences as $a)
        <tr>
            <td>{{ $a->subject->name }}</td>
            <td>{{ $a->date }}</td>
            <td>{{ $a->justified ? 'Sim' : 'Não' }}</td>
        </tr>
    @endforeach
</table>
