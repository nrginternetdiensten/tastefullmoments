# Mail Provider Configuratie

## Overzicht

Twee mail providers zijn geconfigureerd:

- **Transactional**: Voor transactionele mails (bevestigingen, notificaties, wachtwoord resets)
- **Marketing**: Voor marketing mails (nieuwsbrieven, promoties, campagnes)

## Environment Variabelen

Voeg deze variabelen toe aan je `.env` bestand:

### Transactional Mail

```env
MAIL_TRANSACTIONAL_HOST=smtp.example.com
MAIL_TRANSACTIONAL_PORT=587
MAIL_TRANSACTIONAL_USERNAME=transactional@example.com
MAIL_TRANSACTIONAL_PASSWORD=your-password
MAIL_TRANSACTIONAL_SCHEME=tls
```

### Marketing Mail

```env
MAIL_MARKETING_HOST=smtp.mailchimp.com
MAIL_MARKETING_PORT=587
MAIL_MARKETING_USERNAME=marketing@example.com
MAIL_MARKETING_PASSWORD=your-password
MAIL_MARKETING_SCHEME=tls
```

## Gebruik

### Transactionele Mail Versturen

```php
use App\Mail\TransactionalMail;
use Illuminate\Support\Facades\Mail;

Mail::to('user@example.com')->send(
    new TransactionalMail(
        subject: 'Je bestelling is bevestigd',
        data: [
            'title' => 'Bestelling Bevestiging',
            'greeting' => 'Beste Jan,',
            'content' => 'Je bestelling #12345 is succesvol ontvangen en wordt binnenkort verzonden.',
            'actionText' => 'Bekijk Bestelling',
            'actionUrl' => route('orders.show', 12345),
            'footer' => 'Bedankt voor je aankoop!',
            'disclaimer' => 'Dit is een geautomatiseerde e-mail. Antwoorden op deze e-mail worden niet gelezen.',
        ]
    )
);
```

### Marketing Mail Versturen

```php
use App\Mail\MarketingMail;
use Illuminate\Support\Facades\Mail;

Mail::to('subscriber@example.com')->send(
    new MarketingMail(
        subject: 'Onze nieuwe collectie is hier!',
        data: [
            'title' => 'Nieuwe Collectie',
            'greeting' => 'Hallo!',
            'content' => 'Ontdek onze nieuwste collectie met geweldige producten.',
            'actionText' => 'Shop Nu',
            'actionUrl' => 'https://example.com/collectie',
            'sections' => [
                [
                    'title' => 'Nieuwe Producten',
                    'content' => 'Bekijk onze nieuwste producten met kortingen tot 50%.',
                ],
                [
                    'title' => 'Exclusieve Aanbieding',
                    'content' => 'Gebruik code NIEUW25 voor 25% korting op je eerste bestelling.',
                ],
            ],
            'footer' => 'Tot snel!',
            'unsubscribeUrl' => route('newsletter.unsubscribe', ['token' => 'abc123']),
        ]
    )
);
```

## Template Variabelen

### TransactionalMail (emails.transactional)

- `title` - Hoofdtitel (optioneel, default: "Transactionele E-mail")
- `greeting` - Begroeting (optioneel, default: "Beste,")
- `content` - Hoofdinhoud (HTML ondersteund)
- `actionText` - Tekst voor actieknop (optioneel)
- `actionUrl` - URL voor actieknop (optioneel)
- `footer` - Footer tekst (optioneel)
- `disclaimer` - Disclaimer tekst in panel (optioneel)

### MarketingMail (emails.marketing)

- `title` - Hoofdtitel (optioneel, default: "Nieuwsbrief")
- `greeting` - Begroeting (optioneel, default: "Hallo!")
- `content` - Hoofdinhoud (HTML ondersteund)
- `actionText` - Tekst voor actieknop (optioneel)
- `actionUrl` - URL voor actieknop (optioneel)
- `sections` - Array van secties met `title` en `content` (optioneel)
- `footer` - Footer tekst (optioneel)
- `unsubscribeUrl` - Afmeld link (optioneel)

## Queue

Beide mail classes gebruiken `ShouldQueue` interface, dus mails worden asynchroon verzonden via de queue.

Start de queue worker:

```bash
php artisan queue:work
```

## Testen

Test je mail configuratie:

```bash
php artisan tinker
```

```php
Mail::to('test@example.com')->send(
    new \App\Mail\TransactionalMail(
        subject: 'Test Email',
        data: ['content' => 'Dit is een test.']
    )
);
```
