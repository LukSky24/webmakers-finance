# Webmakers Finance - System Ostrzeżeń

Aplikacja Symfony zbudowana w architekturze DDD do zarządzania modelami finansowymi i generowania ostrzeżeń.

## Wymagania wstępne

- PHP 8.4+
- Symfony 7.3+
- PostgreSQL 16+
- Composer
- Docker & Docker Compose
- Bazowanie na szkielecie aplikacji: https://github.com/dunglas/symfony-docker

## Instalacja i uruchomienie

1. Sklonuj repozytorium:
```bash
git clone https://github.com/LukSky24/webmakers-finance.git
cd webmakers-finance
```

2. Uruchom aplikację:
```bash
docker compose up -d
```

3. Zainstaluj zależności:
```bash
docker compose exec php composer install
```

4. Uruchom migracje:
```bash
docker compose exec php php bin/console doctrine:migrations:migrate
```

5. Załaduj przykładowe dane:
```bash
docker compose exec php php bin/console doctrine:fixtures:load
```

## Uruchomienie komendy generowania ostrzeżeń

```bash
docker compose exec php php bin/console app:warnings:generate
```

## Architektura

Aplikacja zbudowana jest w architekturze DDD (Domain-Driven Design) z podziałem na moduły:

### Moduł Finance
- **Contractor** - kontrahenci z nazwą i timestampami
- **Invoice** - faktury z numerem, kontrahentem, kwotą i terminem płatności
- **Budget** - budżety z nazwą i bieżącym saldem

### Moduł Core
- **Warning** - ostrzeżenia z referencją do obiektu i typem

### Reguły ostrzeżeń
1. **Kontrahent** - ostrzeżenie gdy suma nieopłaconych faktur przekracza 15,000
2. **Faktura** - ostrzeżenie gdy faktura jest przeterminowana (nieopłacona i termin minął)
3. **Budżet** - ostrzeżenie gdy bieżący stan budżetu jest ujemny

## Decyzje projektowe

### Architektura DDD
- Podział na moduły (Core, Finance) z jasno określonymi granicami
- Warstwy: domena, aplikacja, infrastruktura
- Repozytoria jako interfejsy w domenie, implementacje w infrastrukturze

### Embedded Value Objects
- `Timestamp` jako embedded object dla wszystkich encji
- `ObjectReference` i `WarningType` jako value objects

### Proste ID
- Użycie `int` zamiast UUID dla prostoty
- Auto-increment w bazie danych

### Soft Delete
- Implementacja miękkiego usuwania przez `deletedAt` w `Timestamp`
- Wszystkie zapytania filtrują usunięte rekordy

## Przykładowe dane

Fixtures zawierają:
- 4 kontrahentów
- 4 budżety (2 z ujemnym saldem)
- 8 faktur (różne statusy płatności i terminy)
- 3 istniejące ostrzeżenia

## Testowanie

Po uruchomieniu fixtures możesz przetestować system:

1. Uruchom komendę generowania ostrzeżeń
2. Sprawdź wyniki w bazie danych
3. Zmień dane (np. opłać fakturę) i uruchom ponownie
4. Obserwuj jak ostrzeżenia są dodawane/usuwane

## Rozwój

### Dodawanie nowych typów ostrzeżeń
1. Dodaj nowy case do `WarningType` enum
2. Utwórz generator implementujący `WarningGeneratorInterface`
3. Zarejestruj generator w komendzie CLI

### Dodawanie nowych encji
1. Utwórz encję w odpowiednim module
2. Dodaj repozytorium (interfejs + implementacja)
3. Utwórz migrację
4. Dodaj do fixtures jeśli potrzebne
