<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Type Field Visibility Demo</title>
    </head>
    <body>
        <main style="padding: 24px;">
            <h1>Type Field Visibility Demo</h1>

            <form style="margin-top: 24px;">
                <p>
                    <label for="type_val">Type value</label><br>
                    <select id="type_val" name="type_val">
                        <option value="1">Type 1</option>
                        <option value="2">Type 2</option>
                        <option value="3">Type 3</option>
                        <option value="4">Type 4</option>
                        <option value="5">Type 5</option>
                    </select>
                </p>

                <p style="margin-top: 16px;">
                    <label for="input_1">input_1</label><br>
                    <input id="input_1" name="input_1" value="Visible for type 1">
                </p>

                <p style="margin-top: 16px;">
                    <button type="button" name="button_1">button_1</button>
                </p>

                <p style="margin-top: 16px;">
                    <label for="input_2">input_2</label><br>
                    <input id="input_2" name="input_2" value="Visible for type 2">
                </p>

                <p style="margin-top: 16px;">
                    <button type="button" name="button_12">button_12</button>
                </p>

                <p style="margin-top: 16px;">
                    <button type="button" name="button_28">button_28</button>
                </p>

                <p style="margin-top: 16px;">
                    <label for="input_3">input_3</label><br>
                    <input id="input_3" name="input_3" value="Visible for type 3">
                </p>

                <p style="margin-top: 16px;">
                    <label for="input_4">input_4</label><br>
                    <input id="input_4" name="input_4" value="Visible for type 4">
                </p>

                <p style="margin-top: 16px;">
                    <label for="input_5">input_5</label><br>
                    <input id="input_5" name="input_5" value="Visible for type 5">
                </p>
            </form>

            <div style="margin-top: 32px;">
                <h2>Expected matches</h2>

                <ul>
                    <li>Type 1: input_1, button_1, button_12</li>
                    <li>Type 2: input_2, button_12, button_28</li>
                    <li>Type 3: input_3</li>
                    <li>Type 4: input_4</li>
                    <li>Type 5: input_5</li>
                </ul>
            </div>

            <p style="margin-top: 32px;">
                <a href="{{ url('/js/type-field-visibility.js') }}">Open script</a>
            </p>
        </main>

        <script src="{{ url('/js/type-field-visibility.js') }}"></script>
    </body>
</html>
