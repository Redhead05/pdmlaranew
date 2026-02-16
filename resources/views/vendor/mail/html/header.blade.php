@props(['url'])
<tr>
    <td class="header" style="padding: 25px 0; text-align: center;">
        <a href="{{ $url }}" style="display: inline-block; text-align: center; text-decoration: none;">
            <img
                src="{{ asset('assets/logotab.png') }}"
                alt="{{ config('app.name') }}"
                style="display: block; margin: 0 auto 8px auto; height: 60px; width: auto;"
            >
            <div style="font-size: 14px; line-height: 1.4; color: #3d4852; font-weight: 600;">
                {{ config('app.name') }}
            </div>
        </a>
    </td>
</tr>
